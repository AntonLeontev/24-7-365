let form = document.querySelector('#agree-form');

let checkboxes = form.querySelectorAll('input[type="checkbox"]');

checkboxes.forEach((check) => {
	check.addEventListener("input", (event) => {
        checkButton();
    });
});

function checkButton() {
	let checked = true;

	checkboxes.forEach((check) => {
		if (check.checked) return;
		
		checked = false;
	});

	form.querySelector("button").disabled = ! checked;
}

