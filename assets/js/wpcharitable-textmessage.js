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
modalBtns1.forEach(function (btn) {
  btn.onclick = function () {
	let modal1 = btn.getAttribute("data-modal");
	document.getElementById(modal1).style.display = "block";
  };
});
let closeBtns1 = [...document.querySelectorAll(".close")];
closeBtns1.forEach(function (btn) {
  btn.onclick = function () {
	let modal1 = btn.closest(".modal-mobile");
	modal1.style.display = "none";
  };
});
window.onclick = function (event) {
  if (event.target.className === "modal-mobile") {
	event.target.style.display = "none";
  }
};