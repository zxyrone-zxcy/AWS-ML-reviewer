// The site has no JS build step (plain CSS/JS in public/). On Vercel, copy the static
// assets into dist/ so the build succeeds whichever output directory the project uses.
import { cpSync, rmSync } from 'node:fs';

rmSync('dist', { recursive: true, force: true });
cpSync('public', 'dist', {
  recursive: true,
  filter: (src) => !src.endsWith('.php') && !src.endsWith('.htaccess'),
});
console.log('Static assets copied to dist/');
