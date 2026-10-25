const { spawnSync } = require('child_process');
const path = require('path');
const fs = require('fs');

const htmlPath = path.resolve(__dirname, 'erd_preview.html');
const pdfPath = path.resolve(__dirname, 'ERD_Aplikasi_Pencatatan_Akte_Purwobinangun.pdf');

const chromePaths = [
    'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
    'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
    'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe'
];

let browserPath = chromePaths.find(p => fs.existsSync(p));

if (!browserPath) {
    console.error('Browser binary not found');
    process.exit(1);
}

console.log('Using browser:', browserPath);
console.log('HTML source:', htmlPath);
console.log('PDF destination:', pdfPath);

const fileUrl = 'file:///' + htmlPath.replace(/\\/g, '/');

const args = [
    '--headless=new',
    '--disable-gpu',
    '--no-pdf-header-footer',
    `--print-to-pdf=${pdfPath}`,
    fileUrl
];

const result = spawnSync(browserPath, args, { stdio: 'inherit' });

if (fs.existsSync(pdfPath)) {
    const stats = fs.statSync(pdfPath);
    console.log(`SUCCESS: ERD PDF generated successfully! Size: ${stats.size} bytes (${(stats.size / 1024).toFixed(2)} KB)`);
} else {
    console.error('FAILED: PDF file was not created. Exit code:', result.status);
    process.exit(1);
}
