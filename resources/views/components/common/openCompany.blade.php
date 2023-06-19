@props(['id', 'overflow' => ''])

<x-common.modal id="{{ $id }}" modalTitle="Оформить ИП / ООО?">
	<p class="fs-8 fs-md-7 mb-13">Оформите заявку, с вами свяжется работник банка и вам бесплатно помогут в
		оформлении ИП или ООО</p>
	<form action="" method="POST" id="open-company-form">
		@csrf
		<div class="d-flex flex-column">
			<x-common.form.select class="mb-13 w-100 w-lg-50" name='org_form' label="Форма собственности">
				<option value="ltd">ООО</option>
				<option value="fe">ИП</option>
			</x-common.form.select>
			<div class="d-flex flex-column flex-lg-row gap-2">
				<x-common.form.input class="mb-12 w-100" name='inn' label="ИНН директора/физ.лица для ИП" />
				<x-common.form.input class="mb-12 w-100" name='snils' label="СНИЛС" />
			</div>
			<div class="d-flex flex-column flex-lg-row gap-2">
				<x-common.form.input class="mb-12 w-100" name='last_name' label="Фамилия директора/физ.лица для ИП" />
				<x-common.form.input class="mb-12 w-100" name='first_name' label="Имя директора/физ.лица для ИП" />
				<x-common.form.input class="mb-12 w-100" name='second_name' label="Отчество директора/физ.лица для ИП" />
			</div>
			<div class="d-flex flex-column flex-lg-row gap-2">
				<x-common.form.input class="mb-12 w-100" type="date" pattern="\d{4}-\d{2}-\d{2}" name='birthday' label="Дата рождения директора/физ.лица для ИП" />
				<x-common.form.input class="mb-12 w-100" name='telephone' label="Номер телефона" />
			</div>
		</div>
		
		<div class="d-flex flex-column">
			<div class="d-flex flex-column flex-lg-row gap-2">
				<x-common.form.select class="mb-12 w-100" name='typeDoc' label="Тип документа">
					<option value="21">Паспорт РФ</option>
					<option value="22">Загранпаспорт гражданина РФ</option>
					<option value="10">Иностранный паспорт</option>
					<option value="14">Временное удостоверение личности гражданина РФ</option>
					<option value="12">Вид на жительство</option>
				</x-common.form.select>
			</div>
			<div class="d-flex flex-column flex-lg-row gap-2">
				<x-common.form.input class="mb-12 w-100" name='serial' label="Серия" />
				<x-common.form.input class="mb-12 w-100" name='number' label="Номер" />
				<x-common.form.input class="mb-12 w-100" type="date" pattern="\d{4}-\d{2}-\d{2}" name='dateStart' label="Дата выдачи" />
			</div>
			<div class="d-flex flex-column flex-lg-row gap-2">
				<x-common.form.input class="mb-12 w-100 d-none" type="date" pattern="\d{4}-\d{2}-\d{2}" name='dateEnd' label="Дата окончания дейстивия документа" />
				<x-common.form.input class="mb-12 w-100 d-none" name='issuedBy' label="Кем выдан документ" />
			</div>
		</div>

		<button class="btn btn-primary w-100 mb-2" disabled>Отправить</button>
		<x-common.form.checkbox class="fs-9 fs-md-8" name="check" label=''>
			Я согласен на <a class="text-reset" href="#">обработку персональных данных</a> и ознакомлен с <a
				class="text-reset" href="#">политикой конфиденциальности</a>.
		</x-common.form.checkbox>
	</form>

	
	<div class="toast-container position-fixed bottom-0 end-0 py-3 px-4" style="z-index: 3">
		<div id="createCompanyToast" class="toast text-bg-dark border border-primary d-none z-3" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="toast-body position-relative text-primary p-3">
				<span class="toast-text">
					Hello, world! This is a toast message. Hello, world! This is a toast message. Hello, world! This is a toast message. Hello, world! This is a toast message.
				</span>
				<button type="button" class="btn-close position-absolute top-0 start-100 translate-middle" id="toastClose" aria-label="Close"></button>
			</div>
		</div>
	</div>
</x-common.modal>

<style>
	
</style>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		let form = document.querySelector('#open-company-form');
		form.addEventListener('submit', submitCompanyForm);
	
		let orgForm = form.querySelector('[name="org_form"]');
		let innLabel = form.querySelector('[name="inn"]').nextElementSibling;
		let snilsLabel = form.querySelector('[name="snils"]').nextElementSibling;
		let lastNameLabel = form.querySelector('[name="last_name"]').nextElementSibling;
		let firsNameLabel = form.querySelector('[name="first_name"]').nextElementSibling;
		let secondNameLabel = form.querySelector('[name="second_name"]').nextElementSibling;
		let check = form.querySelector('[name="check"]');
		let btn = form.querySelector('button');
	
	
		let typeDoc = form.querySelector('[name="typeDoc"]');
		let serial = form.querySelector('[name="serial"]').closest('.form-input');
		let dateEnd = form.querySelector('[name="dateEnd"]').closest('.form-input');
		let issuedBy = form.querySelector('[name="issuedBy"]').closest('.form-input');
	
		orgForm.addEventListener('change', changeOrgForm);
		typeDoc.addEventListener('change', changeDocument);
		check.addEventListener('change', toggleButton);

		const toastClose = document.getElementById('toastClose')
		const toastText = document.querySelector('.toast-text')
		const toast = document.getElementById('createCompanyToast')
	
		if (toastClose) {
			toastClose.addEventListener('click', () => {
				toastHide()
			})
		}
	
		changeDocument();
		changeOrgForm();
		toggleButton();
		toastShow();

		function changeDocument() {
			if (typeDoc.value != 21) {
				dateEnd.classList.remove('d-none');
			} else {
				dateEnd.classList.add('d-none');
			}
	
			if (typeDoc.value == 10) {
				issuedBy.classList.remove('d-none');
			} else {
				issuedBy.classList.add('d-none');
			}
			
			if (['21', '22', '12'].includes(typeDoc.value)) {
				serial.classList.remove('d-none');
			} else {
				serial.classList.add('d-none');
			}
		}
		function changeOrgForm() {
			if (orgForm.value === 'ltd') {
				innLabel.innerText = 'ИНН директора';
				snilsLabel.innerText = 'СНИЛС директора';
				lastNameLabel.innerText = 'Фамилия директора';
				firsNameLabel.innerText = 'Имя директора';
				secondNameLabel.innerText = 'Отчество директора';
				return;
			}
	
			innLabel.innerText = 'ИНН';
			snilsLabel.innerText = 'СНИЛС';
			lastNameLabel.innerText = 'Фамилия';
			firsNameLabel.innerText = 'Имя';
			secondNameLabel.innerText = 'Отчество';
		}
		function toggleButton() {
			if (check.checked) {
				btn.disabled = false;
				return;
			}
	
			btn.disabled = true;
		}
		function toastShow(text, delay = null) {
			toastText.innerHTML = text;
			toast.classList.remove('d-none');

			if (!delay) return;

			setTimeout(toastHide, delay);
		}
		function toastHide() {
			toast.classList.add('d-none');
		}
		function submitCompanyForm(event) {
			event.preventDefault();
			axios
				.post('/register-company', new FormData(form))
				.then((response) => {
					if (typeof response.data.data === 'number') {
						toastShow('Заявка принята. Номер заявки: ' + response.data.data);
						return;
					}

					if (typeof response.data === 'object') {
						let text = '';
						response.data.forEach(message => text += `${message}<br>`)
						toastShow(text);
					}
				})
				.catch(error => { 
					if (error.response?.data?.errors) {
						let text = '';

						for (const key in error.response.data.errors) {
							const errArr = error.response.data.errors[key];

							errArr.forEach(error => {
								text += error;
								text += '<br>';
							});
						}

						toastShow(text);
					}
				})
		}
	})

</script>
