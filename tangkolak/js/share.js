const facebookBtn = document.querySelector(".facebook-btn");
const twitterBtn = document.querySelector(".twitter-btn");
const pinterestBtn = document.querySelector(".pinterest-btn");
const linkedinBtn = document.querySelector(".linkedin-btn");
const whatsappBtn = document.querySelector(".whatsapp-btn");
/*https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fstackoverflow.com%2Fq%2F20956229%2F1101509&picture=http%3A%2F%2Fwww.applezein.net%2Fwordpress%2Fwp-content%2Fuploads%2F2015%2F03%2Ffacebook-logo.jpg&title=A+nice+question+about+Facebook&quote=Does+anyone+know+if+there+have+been+recent+changes+which+could+have+suddenly+stopped+this+from+working%3F&description=Apparently%2C+the+accepted+answer+is+not+correct.*/

/*
contoh buat online
https://www.facebook.com/sharer.php?u=https%3A%2F%2Ftkjb.or.id%2Ftangkolak%2Flihat_kegiatan.php%3Fid_kegiatan%3D2&picture=https://tkjb.or.id/images/foto_konten/kegiatan/KEG_51bf552.jpg&title=Ayo%20Kita%20Baca%20Berita%20Kegiatan%20Terbaru%20Di%20Tangkolak
https://www.facebook.com/sharer.php?u=https%3A%2F%2Ftkjb.or.id%2Ftangkolak%2Flihat_kegiatan.php%3Fid_kegiatan%3D2&picture=https://tkjb.or.id/images/foto_konten/kegiatan/KEG_51bf552.jpg&title=Ayo%20Kita%20Baca%20Berita%20Kegiatan%20Terbaru%20Di%20Tangkolak&quote=Saya+Membagikan+Berita+Terbaru+Di+Pantai+Tangkolak+Klik+Link+Untuk+Membaca&description=Apparently%2C+the+accepted+answer+is+not+correct.
*/
function init() {
  const foto = document.querySelector(".foto");

  let postUrl = encodeURI(document.location.href);
  let postTitle = encodeURI("Saya Membagikan Berita Kegiatan Terbaru Di Tangkolak, Klik Link Ini Untuk Membacanya : ");
  let postImg = encodeURI(foto.src);

  facebookBtn.setAttribute(
    "href",
    `https://www.facebook.com/sharer.php?u=${postUrl}&picture=${postImg}&title=Ayo+Kita+Baca+Berita+Kegiatan+Terbaru+Di+Tangkolak&quote=Saya+Membagikan+Berita+Terbaru+Di+Pantai+Tangkolak+Klik+Link+Ini+Untuk+Mulai+Membaca.`
  );

  twitterBtn.setAttribute(
    "href",
    `https://twitter.com/share?url=${postUrl}&text=${postTitle}`
  );

//   pinterestBtn.setAttribute(
//     "href",
//     `https://pinterest.com/pin/create/bookmarklet/?media=${postImg}&url=${postUrl}&description=${postTitle}`
//   );

//   linkedinBtn.setAttribute(
//     "href",
//     `https://www.linkedin.com/shareArticle?url=${postUrl}&title=${postTitle}`
//   );

  whatsappBtn.setAttribute(
    "href",
    `https://wa.me/?text=${postTitle} ${postUrl}`
  );
}

init();