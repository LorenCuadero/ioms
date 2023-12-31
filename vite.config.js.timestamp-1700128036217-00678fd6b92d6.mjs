// vite.config.js
import { defineConfig } from "file:///var/www/ioms/node_modules/vite/dist/node/index.js";
import laravel from "file:///var/www/ioms/node_modules/laravel-vite-plugin/dist/index.mjs";
import dotenv from "file:///var/www/ioms/node_modules/dotenv/lib/main.js";
import vue from "file:///var/www/ioms/node_modules/@vitejs/plugin-vue/dist/index.mjs";
dotenv.config();
var viteConfig = {
  plugins: [
    vue(),
    laravel({
      input: [],
      refresh: true
    })
  ],
  server: {
    host: "0.0.0.0",
    port: process.env.VITE_SERVE_PORT
  }
};
if (process.env.VITE_LOCAL_IP) {
  viteConfig["server"]["hmr"] = {
    host: process.env.VITE_LOCAL_IP,
    clientPort: process.env.VITE_SERVE_PORT
  };
}
var vite_config_default = defineConfig(viteConfig);
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCIvdmFyL3d3dy9pb21zXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCIvdmFyL3d3dy9pb21zL3ZpdGUuY29uZmlnLmpzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy92YXIvd3d3L2lvbXMvdml0ZS5jb25maWcuanNcIjtpbXBvcnQgeyBkZWZpbmVDb25maWcgfSBmcm9tICd2aXRlJztcbmltcG9ydCBsYXJhdmVsIGZyb20gJ2xhcmF2ZWwtdml0ZS1wbHVnaW4nO1xuaW1wb3J0IGRvdGVudiBmcm9tICdkb3RlbnYnO1xuaW1wb3J0IHZ1ZSBmcm9tICdAdml0ZWpzL3BsdWdpbi12dWUnO1xuXG5kb3RlbnYuY29uZmlnKCk7XG5cbnZhciB2aXRlQ29uZmlnID0ge1xuICAgIHBsdWdpbnM6IFtcbiAgICAgICAgdnVlKCksXG4gICAgICAgIGxhcmF2ZWwoe1xuICAgICAgICAgICAgaW5wdXQ6IFsncmVzb3VyY2VzL2Nzcy9jb21waWxlLmNzcycsICdyZXNvdXJjZXMvanMvY29tcGlsZS5qcyddLFxuICAgICAgICAgICAgcmVmcmVzaDogdHJ1ZSxcbiAgICAgICAgfSksXG4gICAgXSxcbiAgICBzZXJ2ZXI6IHtcbiAgICAgICAgaG9zdDogJzAuMC4wLjAnLFxuICAgICAgICBwb3J0OiBwcm9jZXNzLmVudi5WSVRFX1NFUlZFX1BPUlRcbiAgICB9XG59O1xuXG5pZiAocHJvY2Vzcy5lbnYuVklURV9MT0NBTF9JUCkge1xuICAgIHZpdGVDb25maWdbJ3NlcnZlciddWydobXInXSA9IHtcbiAgICAgICAgaG9zdDogcHJvY2Vzcy5lbnYuVklURV9MT0NBTF9JUCxcbiAgICAgICAgY2xpZW50UG9ydDogcHJvY2Vzcy5lbnYuVklURV9TRVJWRV9QT1JULFxuICAgIH07XG59XG5cbmV4cG9ydCBkZWZhdWx0IGRlZmluZUNvbmZpZyh2aXRlQ29uZmlnKTtcbiJdLAogICJtYXBwaW5ncyI6ICI7QUFBeU4sU0FBUyxvQkFBb0I7QUFDdFAsT0FBTyxhQUFhO0FBQ3BCLE9BQU8sWUFBWTtBQUNuQixPQUFPLFNBQVM7QUFFaEIsT0FBTyxPQUFPO0FBRWQsSUFBSSxhQUFhO0FBQUEsRUFDYixTQUFTO0FBQUEsSUFDTCxJQUFJO0FBQUEsSUFDSixRQUFRO0FBQUEsTUFDSixPQUFPLENBQUMsNkJBQTZCLHlCQUF5QjtBQUFBLE1BQzlELFNBQVM7QUFBQSxJQUNiLENBQUM7QUFBQSxFQUNMO0FBQUEsRUFDQSxRQUFRO0FBQUEsSUFDSixNQUFNO0FBQUEsSUFDTixNQUFNLFFBQVEsSUFBSTtBQUFBLEVBQ3RCO0FBQ0o7QUFFQSxJQUFJLFFBQVEsSUFBSSxlQUFlO0FBQzNCLGFBQVcsUUFBUSxFQUFFLEtBQUssSUFBSTtBQUFBLElBQzFCLE1BQU0sUUFBUSxJQUFJO0FBQUEsSUFDbEIsWUFBWSxRQUFRLElBQUk7QUFBQSxFQUM1QjtBQUNKO0FBRUEsSUFBTyxzQkFBUSxhQUFhLFVBQVU7IiwKICAibmFtZXMiOiBbXQp9Cg==
