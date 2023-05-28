import * as bootstrap from "bootstrap";
import.meta.glob(["../images/**", "../fonts/**"]);


document.addEventListener('DOMContentLoaded', () => {
	window.addEventListener("scroll", handleScroll);

    handleScroll();

	$("#slider").slick({
        slidesToShow: 1,
        infinite: true,
        autoplay: true,
        variableWidth: true,
        mobileFirst: true,
        arrows: false,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    centerMode: true,
                    centerPadding: "0",
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    variableWidth: false,
                    arrows: true,
                },
            },
        ],
    });

    document
        .querySelector(".faq__questions")
        .addEventListener("click", questionsClick);


	/** Переключение точек на карте */
	let bordered = Array.from(document.querySelectorAll('.bordered'));
	bordered.pop();
	bordered.shift();

	function cycleRoadmap() {
        let point = bordered[count];

        point.classList.add("bordered_active");
        setTimeout(() => {
            point.classList.remove("bordered_active");
        }, 800);

        count++;

        if (count === bordered.length) {
            count = 0;
        }
    }

	let count = 0;

	setInterval(cycleRoadmap, 800);

});

function handleScroll() {
	colorHeader();
	setTimeout(setNavigation(), 50);
}
function colorHeader() {
	let header = document.querySelector(".header");

	if (scrollY === 0) {
		header.classList.remove("header_scrolled");
		return;
	}

	header.classList.add("header_scrolled");
}
function setNavigation() {
	let anchor;
	let top = null;
	let header = document.querySelector(".header");
    let navDesk = header.querySelector("#nav-desk")?.children;
    let navMob = header.querySelector("#nav-mobile")?.children;
	let anchors = document.querySelectorAll(".anchor");

	anchors.forEach((el) => {
		if (el.getBoundingClientRect().top > 150) return;

		if (top === null && el.getBoundingClientRect().top <= 150) {
			anchor = el.getAttribute("id");
			return;
		}

		if (el.getBoundingClientRect().top > top) {
			anchor = el.getAttribute("id");
		}
	});

	let activeMobile = activateNavItem(navMob, anchor);
	let activeDesktop = activateNavItem(navDesk, anchor);
	moveMarkerMobileNav(activeMobile);
	moveMarkerDesktopNav(activeDesktop);
}
function activateNavItem(nav, anchor) {
	let activeItem = null;

	for (const item of nav) {
		if (anchor === undefined || item.dataset.anchor !== anchor) {
			item.classList.remove("nav__item_active");
			continue;
		}

		item.classList.add("nav__item_active");
		activeItem = item;
	}

	return activeItem;
}
function moveMarkerMobileNav(element) {
	let header = document.querySelector(".header");
    let mobileHeaderMarker = header.querySelector(".nav-mobile__marker");

	if (element === null) {
		mobileHeaderMarker.style.top = "-66px";
		return;
	}

	let top =
		element.getBoundingClientRect().top -
		document.querySelector("#nav-mobile").getBoundingClientRect()
			.top;

	mobileHeaderMarker.style.top = top + "px";
}
function moveMarkerDesktopNav(element) {
	let header = document.querySelector(".header");
	let desktopHeaderMarker = header.querySelector(".nav-desk__marker");

	if (element === null) {
		desktopHeaderMarker.style.left = "-66px";
		return;
	}

	let left =
		element.getBoundingClientRect().left -
		document.querySelector("#nav-desk").getBoundingClientRect()
			.left +
		element.getBoundingClientRect().width / 2 -
		desktopHeaderMarker.getBoundingClientRect().width / 2;

	desktopHeaderMarker.style.left = left + "px";
}
function questionsClick(event) {
	let target = event.target.closest(".question__title");

	if (!target) return;

	let answer =
		target.parentElement.querySelector(".question__answer");
	let parent = target.parentElement;

	if (
		parent.getBoundingClientRect().height >
		target.getBoundingClientRect().height
	) {
		parent.style.height =
			target.getBoundingClientRect().height + "px";
		parent.classList.remove("question_active");
		return;
	}

	parent.style.height = target.getBoundingClientRect().height + "px";
	parent.classList.add("question_active");

	answer.style.display = "block";
	let height = answer.getBoundingClientRect().height;

	parent.style.height =
		target.getBoundingClientRect().height + height + "px";
}














