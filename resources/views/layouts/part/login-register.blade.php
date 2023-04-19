<x-auth.box>
	<div ref="root" data-tab="{{ request()->route()->getName() }}">
		<div class="auth-tabs" ref="tabs">
			<button 
				class="btn px-0 auth-tab" 
				:class="{'auth-tab_active': tab === 'login'}" 
				@click="tab = 'login'"
				ref="login-tab"
			>Вход</button>
			<button 
				class="btn px-0 auth-tab" 
				:class="{'auth-tab_active': tab === 'register'}" 
				@click="tab = 'register'"
				ref="register-tab"
			>Регистрация</button>
			<div class="auth-tabs__marker" ref="tabMarker"></div>
		</div>

		<div class="auth-forms">
			<div class="auth-form auth-form_login" :class="{'auth-form_active': tab === 'login'}">
				<form  method="POST" action="{{ route('login') }}" class="login-form" ref="loginForm">
					<x-common.form.input name="email" placeholder="e-mail" required />
					<x-common.form.input class="mb-13" type="password" name="password" placeholder="Пароль" required />
					<button 
						class="btn btn-primary w-100 mb-12" 
						ref="loginButton"
						@click.prevent="handleForm"
					>Войти</button>
					<div class="d-flex justify-content-between align-items-center gap-3">
						<x-common.form.checkbox name="remember">
							Запомнить меня
						</x-common.form.checkbox>

						<a class="btn btn-link text-white" href="{{ route('password.request') }}">
							{{ __('Forgot Your Password?') }}
						</a>
					</div>
				</form>
			</div>
			<div class="auth-form auth-form_register" :class="{'auth-form_active': tab === 'register'}">
				<form method="POST" action="{{ route('register') }}" ref="registerForm">
					<x-common.form.input name="email" placeholder="e-mail" required />
					
					<x-common.form.input type="password" name="password" placeholder="Пароль" required />
					
					<x-common.form.input class="mb-13" type="password" name="password_confirmation" placeholder="Повторите пароль" required />
					
					<button 
						class="btn btn-primary w-100 mb-12" 
						ref="registerButton"
						@click.prevent="handleForm"
					>Создать аккаунт</button>
					
					<p class="text-center text-light fs-8">
						Нажимая кнопку “Создать аккаунт”, вы соглашаетесь с <a href="#" class="text-white">Условиями использования</a> и <a href="#" class="text-white">Политикой конфиденциальности</a>.
					</p>
				</form>
			</div>
		</div>
	</div>
</x-auth.box>
