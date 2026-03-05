import json
import sys
import os
import base64
import requests
from datetime import datetime


# Google Imagen 3 only supports these aspect ratios — map fal.ai values to closest
GOOGLE_ASPECT_RATIO_MAP = {
    "4:5": "3:4",   # Facebook feed → closest Google equivalent
    "1:1": "1:1",
    "9:16": "9:16",
    "16:9": "16:9",
    "4:3": "4:3",
    "3:4": "3:4",
}


def save_image(img_data, output_dir):
    timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
    filename = f"{output_dir}/{timestamp}.png"
    os.makedirs(output_dir, exist_ok=True)
    with open(filename, "wb") as f:
        f.write(img_data)
    return filename


def generate_with_google(prompt_data, api_key):
    """Generate image using Google AI Studio (Imagen 3). Returns raw bytes."""
    aspect_ratio = prompt_data.get("settings", {}).get("aspect_ratio", "1:1")
    google_aspect = GOOGLE_ASPECT_RATIO_MAP.get(aspect_ratio, "1:1")

    payload = {
        "instances": [
            {
                "prompt": prompt_data["prompt"],
                "negativePrompt": prompt_data.get("negative_prompt", ""),
            }
        ],
        "parameters": {
            "sampleCount": 1,
            "aspectRatio": google_aspect,
            "personGeneration": "allow_adult",
        },
    }

    response = requests.post(
        f"https://generativelanguage.googleapis.com/v1beta/models/imagen-4.0-fast-generate-001:predict?key={api_key}",
        headers={"Content-Type": "application/json"},
        json=payload,
        timeout=60,
    )
    response.raise_for_status()
    result = response.json()

    img_b64 = result["predictions"][0]["bytesBase64Encoded"]
    return base64.b64decode(img_b64)


def generate_with_fal(prompt_data, api_key):
    """Generate image using fal.ai (Nano Banana 2). Returns raw bytes."""
    resolution = prompt_data.get("settings", {}).get("resolution", "1024x1024")

    payload = {
        "prompt": prompt_data["prompt"],
        "negative_prompt": prompt_data.get("negative_prompt", ""),
        "image_size": resolution,
    }

    response = requests.post(
        "https://fal.run/fal-ai/nano-banana-2",
        headers={
            "Authorization": f"Key {api_key}",
            "Content-Type": "application/json",
        },
        json=payload,
        timeout=60,
    )
    response.raise_for_status()
    result = response.json()

    image_url = result["images"][0]["url"]
    img_response = requests.get(image_url, timeout=30)
    img_response.raise_for_status()
    return img_response.content


def generate_image(json_prompt_path, output_dir="images"):
    """Load a JSON prompt, try Google AI Studio first, fall back to fal.ai."""
    with open(json_prompt_path, "r") as f:
        prompt_data = json.load(f)

    GOOGLE_API_KEY = os.environ.get("GOOGLE_API_KEY")
    FAL_KEY = os.environ.get("FAL_KEY")

    if not GOOGLE_API_KEY and not FAL_KEY:
        print("Error: set GOOGLE_API_KEY or FAL_KEY before running.")
        sys.exit(1)

    img_data = None
    provider_used = None

    # 1. Try Google AI Studio (free tier available)
    if GOOGLE_API_KEY:
        try:
            print("Trying Google AI Studio (Imagen 3)...")
            img_data = generate_with_google(prompt_data, GOOGLE_API_KEY)
            provider_used = "Google AI Studio"
        except Exception as e:
            print(f"Google failed ({e}). Falling back to fal.ai...")

    # 2. Fallback to fal.ai
    if img_data is None:
        if not FAL_KEY:
            print("Error: Google failed and FAL_KEY is not set.")
            sys.exit(1)
        try:
            img_data = generate_with_fal(prompt_data, FAL_KEY)
            provider_used = "fal.ai"
        except Exception as e:
            print(f"fal.ai also failed: {e}")
            sys.exit(1)

    filename = save_image(img_data, output_dir)
    print(f"Saved: {filename}  (via {provider_used})")
    return filename


if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python generate.py <path-to-json-prompt> [output-dir]")
        sys.exit(1)

    output_dir = sys.argv[2] if len(sys.argv) > 2 else "images"
    generate_image(sys.argv[1], output_dir)
