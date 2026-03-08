<template>
  <footer class="bg-slate-50 border-t border-slate-200 pt-12 pb-8 px-4 sm:px-6 lg:px-8" role="contentinfo">
    <div class="max-w-7xl mx-auto">
      <!-- Top: logo + links -->
      <div class="flex flex-col md:flex-row md:items-start justify-between gap-10 mb-10">
        <!-- Brand -->
        <div class="shrink-0">
          <a href="/" aria-label="Aula360 - Inicio">
            <span class="text-xl font-bold text-slate-900">Aula<span class="text-blue-600">360</span></span>
          </a>
          <p class="text-slate-500 text-sm mt-2 max-w-xs leading-relaxed">
            Gestión Académica con IA para instituciones educativas colombianas.
          </p>
        </div>

        <!-- Links -->
        <div class="flex flex-wrap gap-x-12 gap-y-8">
          <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-3">
              Plataforma
            </p>
            <ul class="space-y-2">
              <li><a href="/como-funciona" class="text-sm text-slate-600 hover:text-slate-900 transition-colors">Cómo funciona</a></li>
              <li><a href="/#ia" class="text-sm text-slate-600 hover:text-slate-900 transition-colors">Inteligencia Artificial</a></li>
              <li><a href="/#modulos" class="text-sm text-slate-600 hover:text-slate-900 transition-colors">Módulos</a></li>
              <li><a href="/#beneficios" class="text-sm text-slate-600 hover:text-slate-900 transition-colors">Beneficios</a></li>
            </ul>
          </div>
          <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-3">
              Planes
            </p>
            <ul class="space-y-2">
              <li><a href="/pricing" class="text-sm text-slate-600 hover:text-slate-900 transition-colors">Precios</a></li>
              <li><a href="/universidades" class="text-sm text-slate-600 hover:text-slate-900 transition-colors">Universidades</a></li>
              <li><a href="/sector-oficial" class="text-sm text-slate-600 hover:text-slate-900 transition-colors">Sector oficial</a></li>
            </ul>
          </div>
          <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-3">
              Acceso
            </p>
            <ul class="space-y-2">
              <li><a href="/login" class="text-sm text-slate-600 hover:text-slate-900 transition-colors">Iniciar sesión</a></li>
              <li><a href="/#contacto" class="text-sm text-slate-600 hover:text-slate-900 transition-colors">Solicitar demo</a></li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Bottom: copyright -->
      <div class="border-t border-slate-200 pt-6 flex flex-col sm:flex-row items-center justify-between gap-2">
        <p class="text-slate-400 text-xs">
          © {{ new Date().getFullYear() }} Aula360. Todos los derechos reservados.
        </p>
        <p class="text-slate-400 text-xs">
          Desarrollado por
          <a
            href="https://mauriciomontoya.co"
            target="_blank"
            rel="noopener noreferrer"
            class="text-slate-500 hover:text-slate-700 transition-colors"
          >Mauricio Montoya</a>
        </p>
      </div>
    </div>
  </footer>
</template>
