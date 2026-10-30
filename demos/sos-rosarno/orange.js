// Arancia 3D nella hero: ruota verso il puntatore del mouse (o il dito, su mobile).
// Richiede Three.js caricato da cdnjs (vedi index.html). Se WebGL non c'è, la pagina resta leggibile.
(function () {
  const canvas = document.getElementById('orange');
  if (!canvas || !window.THREE) return;

  let renderer;
  try {
    renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
  } catch (e) {
    canvas.remove();
    return;
  }
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.outputColorSpace = THREE.SRGBColorSpace;

  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(35, 1, 0.1, 100);
  camera.position.z = 4.2;

  scene.add(new THREE.AmbientLight(0xffffff, 0.55));
  const key = new THREE.DirectionalLight(0xffffff, 1.9);
  key.position.set(-3, 4, 5);
  scene.add(key);
  const rim = new THREE.DirectionalLight(0xffd7a8, 0.8);
  rim.position.set(4, -2, -3);
  scene.add(rim);

  // Rilievo della buccia: rumore deterministico sulla superficie della sfera.
  function hash(x, y, z) {
    const s = Math.sin(x * 127.1 + y * 311.7 + z * 74.7) * 43758.5453;
    return s - Math.floor(s);
  }
  function noise(x, y, z) {
    const xi = Math.floor(x), yi = Math.floor(y), zi = Math.floor(z);
    const xf = x - xi, yf = y - yi, zf = z - zi;
    const u = xf * xf * (3 - 2 * xf), v = yf * yf * (3 - 2 * yf), w = zf * zf * (3 - 2 * zf);
    const lerp = (a, b, t) => a + (b - a) * t;
    const c = (dx, dy, dz) => hash(xi + dx, yi + dy, zi + dz);
    return lerp(
      lerp(lerp(c(0, 0, 0), c(1, 0, 0), u), lerp(c(0, 1, 0), c(1, 1, 0), u), v),
      lerp(lerp(c(0, 0, 1), c(1, 0, 1), u), lerp(c(0, 1, 1), c(1, 1, 1), u), v),
      w
    );
  }

  const geometry = new THREE.SphereGeometry(1, 128, 128);
  const pos = geometry.attributes.position;
  const v = new THREE.Vector3();
  for (let i = 0; i < pos.count; i++) {
    v.fromBufferAttribute(pos, i).normalize();
    // Buccia leggermente irregolare: piccole cavità fitte e un'ondulazione lenta.
    const dimples = noise(v.x * 9, v.y * 9, v.z * 9);
    const undulation = noise(v.x * 1.6 + 3, v.y * 1.6, v.z * 1.6);
    const r = 1 + (dimples - 0.5) * 0.028 + (undulation - 0.5) * 0.035;
    pos.setXYZ(i, v.x * r, v.y * r, v.z * r);
  }
  geometry.computeVertexNormals();

  // Texture bump a puntini, per la grana della buccia.
  const bumpCanvas = document.createElement('canvas');
  bumpCanvas.width = bumpCanvas.height = 512;
  const ctx = bumpCanvas.getContext('2d');
  ctx.fillStyle = '#808080';
  ctx.fillRect(0, 0, 512, 512);
  for (let i = 0; i < 9000; i++) {
    const shade = 90 + Math.random() * 90;
    ctx.fillStyle = `rgb(${shade},${shade},${shade})`;
    ctx.beginPath();
    ctx.arc(Math.random() * 512, Math.random() * 512, 0.8 + Math.random() * 1.6, 0, Math.PI * 2);
    ctx.fill();
  }
  const bumpMap = new THREE.CanvasTexture(bumpCanvas);
  bumpMap.wrapS = bumpMap.wrapT = THREE.RepeatWrapping;

  const orange = new THREE.Mesh(
    geometry,
    new THREE.MeshStandardMaterial({ color: 0xff7a14, roughness: 0.5, metalness: 0, bumpMap, bumpScale: 1.4 })
  );

  // Picciolo e foglia, in verde scuro.
  const green = new THREE.MeshStandardMaterial({ color: 0x1f5a2e, roughness: 0.7 });
  const stem = new THREE.Mesh(new THREE.CylinderGeometry(0.035, 0.05, 0.22, 12), green);
  stem.position.y = 1.02;
  const leaf = new THREE.Mesh(new THREE.SphereGeometry(0.22, 24, 12), green);
  leaf.scale.set(1.6, 0.35, 0.8);
  leaf.position.set(0.22, 1.12, 0);
  leaf.rotation.z = -0.4;

  const model = new THREE.Group();
  model.add(orange, stem, leaf);
  scene.add(model);

  // Puntatore: coordinate normalizzate -1..1 rispetto alla hero.
  const pointer = { x: 0, y: 0, active: false };
  const target = { x: 0, y: 0 };
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function setPointer(clientX, clientY) {
    const r = canvas.getBoundingClientRect();
    pointer.x = ((clientX - r.left) / r.width) * 2 - 1;
    pointer.y = -(((clientY - r.top) / r.height) * 2 - 1);
    pointer.active = true;
  }
  window.addEventListener('pointermove', (e) => setPointer(e.clientX, e.clientY), { passive: true });
  window.addEventListener('pointerleave', () => { pointer.active = false; });

  function resize() {
    const size = canvas.clientWidth;
    renderer.setSize(size, size, false);
    camera.aspect = 1;
    camera.updateProjectionMatrix();
  }
  new ResizeObserver(resize).observe(canvas);
  resize();

  let t = 0;
  function frame() {
    t += 0.01;
    // Il picciolo guarda verso il puntatore: l'arancia si inclina nella direzione del mouse.
    if (pointer.active) {
      target.y = pointer.x * 1.1;
      target.x = pointer.y * 0.8;
    } else if (!reduceMotion) {
      target.y = Math.sin(t * 0.6) * 0.5;
      target.x = Math.sin(t * 0.4) * 0.15;
    }
    model.rotation.y += (target.y - model.rotation.y) * 0.08;
    model.rotation.x += (target.x - model.rotation.x) * 0.08;
    renderer.render(scene, camera);
    requestAnimationFrame(frame);
  }
  requestAnimationFrame(frame);
})();
