let modalBtns = [...document.querySelectorAll(".button")];
modalBtns.forEach(function (btn) {
  btn.onclick = function () {
	let modal = btn.getAttribute("data-modal");
	document.getElementById(modal).style.display = "block";
  };
});
let closeBtns = [...document.querySelectorAll(".close")];
closeBtns.forEach(function (btn) {
  btn.onclick = function () {
	let modal = btn.closest(".modal");
	modal.style.display = "none";
  };
});
window.onclick = function (event) {
  if (event.target.className === "modal") {
	event.target.style.display = "none";
  }
};


let modalBtns1 = [...document.querySelectorAll(".show-mobile-atag")];
modalBtns1.forEach(function (btn1) {
  btn1.onclick = function () {
	let modal1 = btn1.getAttribute("data-modal1");
	document.getElementById(modal1).style.display = "block";
  };
});
let closeBtns1 = [...document.querySelectorAll(".close-mobile")];
closeBtns1.forEach(function (btn1) {
  btn1.onclick = function () {
	let modal1 = btn1.closest(".modal-mobile");
	modal1.style.display = "none";
  };
});
window.onclick = function (event) {
  if (event.target.className === "modal-mobile") {
	event.target.style.display = "none";
  }
};

// jQuery('.show-mobile').on('click', function() {
  jQuery('.show-mobile').ready(function(){
  jQuery('#mobile-submit-btn').on('click', function() {
    let _href = jQuery("a.show-mobile-atag.btn").attr("href");
    let checkedURL = jQuery('form input[type=radio]:checked').val();
    let newhref = jQuery("a.show-mobile-atag.btn").attr("href", _href + checkedURL);
    console.log(newhref);
    // alert( checkedURL + _href );
  });
});