<template>
  <footer class="bg-slate-50 border-t border-slate-200 py-12 px-4 sm:px-6 lg:px-8" role="contentinfo">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
      <a href="/" aria-label="Aula360 - Inicio">
        <span class="text-xl font-bold text-slate-900">Aula<span class="text-blue-600">360</span></span>
      </a>
      <p class="text-slate-500 text-center text-sm">
        Gestión Académica con IA para instituciones educativas colombianas.
      </p>
      <div class="flex flex-col items-center md:items-end gap-1">
        <p class="text-slate-500 text-sm">
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
