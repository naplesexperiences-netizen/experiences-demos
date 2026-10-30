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
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1.3;

  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(35, 1, 0.1, 100);
  camera.position.z = 4.2;

  scene.add(new THREE.HemisphereLight(0xfff4e6, 0x6b4a2a, 0.9));
  const key = new THREE.DirectionalLight(0xffffff, 2.4);
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

  // Forma: arancia leggermente schiacciata, con la cavità del calice in alto e l'ombelico in basso.
  const geometry = new THREE.SphereGeometry(1, 160, 160);
  const pos = geometry.attributes.position;
  const v = new THREE.Vector3();
  for (let i = 0; i < pos.count; i++) {
    v.fromBufferAttribute(pos, i).normalize();
    const dimples = noise(v.x * 9, v.y * 9, v.z * 9);
    const undulation = noise(v.x * 1.6 + 3, v.y * 1.6, v.z * 1.6);
    const topPit = Math.max(0, v.y - 0.86) / 0.14;      // 0..1 vicino al polo nord
    const navel = Math.max(0, -v.y - 0.9) / 0.1;        // 0..1 vicino al polo sud
    const r = 1 + (dimples - 0.5) * 0.02 + (undulation - 0.5) * 0.04 - topPit * topPit * 0.07 - navel * navel * 0.04;
    pos.setXYZ(i, v.x * r * 1.02, v.y * r * 0.94, v.z * r * 1.02);
  }
  geometry.computeVertexNormals();

  // Buccia da foto reale: colore e rilievo dei pori, piastrellati.
  const loader = new THREE.TextureLoader();
  const peel = loader.load('img/peel-color.webp');
  peel.colorSpace = THREE.SRGBColorSpace;
  const bump = loader.load('img/peel-bump.webp');
  [peel, bump].forEach((t) => {
    t.wrapS = t.wrapT = THREE.RepeatWrapping;
    t.repeat.set(5, 3);
    t.anisotropy = renderer.capabilities.getMaxAnisotropy();
  });

  const orange = new THREE.Mesh(
    geometry,
    new THREE.MeshPhysicalMaterial({
      map: peel,
      color: 0xffeedd,
      bumpMap: bump,
      bumpScale: 0.6,
      roughness: 0.52,
      clearcoat: 0.25,
      clearcoatRoughness: 0.45,
      sheen: 0.2,
      sheenColor: new THREE.Color(0xffb067),
      sheenRoughness: 0.5,
    })
  );

  // Calice verde in alto: piccola stella di sepali e un moncone di picciolo.
  const green = new THREE.MeshStandardMaterial({ color: 0x2f6b34, roughness: 0.65 });
  const calyx = new THREE.Group();
  for (let i = 0; i < 5; i++) {
    const sepal = new THREE.Mesh(new THREE.SphereGeometry(0.09, 16, 8), green);
    sepal.scale.set(2.2, 0.28, 0.9);
    const a = (i / 5) * Math.PI * 2;
    sepal.position.set(Math.cos(a) * 0.11, 0, Math.sin(a) * 0.11);
    sepal.rotation.y = -a;
    sepal.rotation.z = 0.12;
    calyx.add(sepal);
  }
  const stem = new THREE.Mesh(new THREE.CylinderGeometry(0.028, 0.04, 0.14, 12), new THREE.MeshStandardMaterial({ color: 0x5b4a2a, roughness: 0.8 }));
  stem.position.y = 0.05;
  calyx.add(stem);
  calyx.position.y = 0.9;

  // Ombelico in basso: piccolo disco più scuro.
  const navelMark = new THREE.Mesh(
    new THREE.CircleGeometry(0.07, 24),
    new THREE.MeshStandardMaterial({ color: 0xb85a0e, roughness: 0.7 })
  );
  navelMark.rotation.x = Math.PI / 2;
  navelMark.position.y = -0.935;

  const leaf = new THREE.Mesh(new THREE.SphereGeometry(0.24, 24, 12), green);
  leaf.scale.set(1.0, 0.14, 0.55);
  leaf.position.set(0.2, 0.98, 0);
  leaf.rotation.z = -0.5;

  const model = new THREE.Group();
  model.add(orange, calyx, navelMark, leaf);
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
