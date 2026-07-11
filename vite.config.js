import { defineConfig, loadEnv } from 'vite'
import laravel from 'laravel-vite-plugin'
import react from '@vitejs/plugin-react'
import path from 'path'

export default defineConfig(({ mode }) => {
  // Cargamos también las variables sin prefijo VITE_ (ej. APP_NAME) para no
  // tener que duplicarlas en .env solo para exponerlas al frontend.
  const env = loadEnv(mode, process.cwd(), '')

  return {
    plugins: [
      laravel({
        input: [
          'resources/js/app.jsx',
        ],
        refresh: true,
      }),
      react(),
    ],
    resolve: {
      alias: {
        '@': path.resolve(__dirname, 'resources/js'),
      },
    },
    css: {
      devSourcemap: true,
    },
    define: {
      __APP_NAME__: JSON.stringify(env.APP_NAME || 'Laravel'),
    },
  }
})
