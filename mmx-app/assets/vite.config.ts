// @ts-ignore
import withMmx, {aiConfig} from '@vesp/mmx-frontend/config'
import ai from 'unplugin-auto-import/vite'

export default withMmx('mmx-app', {
  plugins: [
    ai({
      ...aiConfig,
      dirs: ['src/mgr/utils'],
    }),
  ],
  build: {
    manifest: 'manifest.json',
    emptyOutDir: true,
    outDir: './dist',
    rollupOptions: {
      input: {mgr: './src/mgr.ts', web: './src/web.ts'},
      output: {
        manualChunks: {
          api: ['@vesp/mmx-frontend/api', '@vesp/mmx-frontend/toast', 'ofetch'],
          vesp: ['@vesp/mmx-frontend'],
          vue: ['vue'],
          'vue-router': ['vue-router'],
          'vue-bootstrap': ['bootstrap-vue-next'],
        },
      },
    },
  },
})
