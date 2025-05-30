// Dumbbell Canvas
const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
const renderer = new THREE.WebGLRenderer({ canvas: document.getElementById('dumbbellCanvas'), alpha: true });
renderer.setSize(window.innerWidth, window.innerHeight);

// Vật liệu màu cam ánh sáng
const material = new THREE.MeshStandardMaterial({ color: 0xffa500, metalness: 0.5, roughness: 0.3 });

// Dumbbell nhỏ gọn hơn
const handle = new THREE.CylinderGeometry(0.05, 0.05, 0.8, 32);
const leftWeight = new THREE.CylinderGeometry(0.15, 0.15, 0.2, 32);
const rightWeight = leftWeight.clone();

const handleMesh = new THREE.Mesh(handle, material);
const leftMesh = new THREE.Mesh(leftWeight, material);
const rightMesh = new THREE.Mesh(rightWeight, material);

leftMesh.position.set(-0.5, 0, 0);
rightMesh.position.set(0.5, 0, 0);

scene.add(handleMesh, leftMesh, rightMesh);

// Ánh sáng
const light = new THREE.PointLight(0xffffff, 1);
light.position.set(2, 2, 3);
scene.add(light);

// Camera lùi xa hơn
camera.position.z = 2.2;

// Hiệu ứng xoay + lắc
let angle = 0;

function animateDumbbell() {
  requestAnimationFrame(animateDumbbell);

  angle += 0.01;
  const zShake = Math.sin(angle * 2) * 0.02;

  [handleMesh, leftMesh, rightMesh].forEach(mesh => {
    mesh.rotation.x += 0.01;
    mesh.rotation.z = zShake;
  });

  renderer.render(scene, camera);
}

animateDumbbell();

// Responsive
window.addEventListener('resize', () => {
  camera.aspect = window.innerWidth / window.innerHeight;
  camera.updateProjectionMatrix();
  renderer.setSize(window.innerWidth, window.innerHeight);
});
