const bar = document.getElementById('bar');
const close = document.getElementById('close');
const nav = document.getElementById('navbar');

if (bar){
	bar.addEventListener('click', () =>{
		nav.classList.add('active')

	})
}

if (close){
	close.addEventListener('click', () =>{
		nav.classList.remove('active')

	})
}
let listProductHTML = document.querySelector('.listProduct');
let listCartHTML = document.querySelector('.listCart');
let iconCart = document.querySelector('.icon-cart');
let iconCartSpan = document.querySelector('.icon-cart span');
let body = document.querySelector('body');
let closeCart = document.querySelector('.close');
let products = [];
let cart = [];



        
    

initApp();