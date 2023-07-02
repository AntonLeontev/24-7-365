<template>
	<div>
            <div class="auth-tabs" ref="tabs">
                <button class="btn auth-tab px-0" :class="{ 'auth-tab_active': tab === 'login' }" @click="tab = 'login'"
                    ref="login-tab">Вход</button>
                <button class="btn auth-tab px-0" :class="{ 'auth-tab_active': tab === 'register' }"
                    @click="tab = 'register'" ref="register-tab">Регистрация</button>
                <div class="auth-tabs__marker" ref="tabMarker"></div>
            </div>

            <div class="auth-forms">
                <div class="forms-container" :class="{ 'forms-container_register': tab === 'register' }">
                    <div class="auth-form auth-form_login" :class="{ 'auth-form_active': tab === 'login' }">
                        <form class="d-flex flex-column gap-2" method="POST" action="/login" ref="loginForm">
							<input-component 
								name="email" 
								placeholder="e-mail" 
								requiered
								:error="errors['email']"
								@clear-error="clearError"
							/>
							<input-component 
								name="password" 
								type="password" 
								placeholder="Пароль"
                                required
								:error="errors['password']"
								@clear-error="clearError"
							/>
                            <button class="btn btn-primary w-100 mt-12 mb-12" ref="loginButton"
                                @click.prevent="handleForm">Войти</button>
                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
								<checkbox name="remember">Запомнить меня</checkbox>

                                <a class="btn btn-link text-white pe-0" href="/password/reset">
                                    Забыли пароль?
                                </a>
                            </div>
                        </form>
                    </div>
                    <div class="auth-form auth-form_register" :class="{ 'auth-form_active': tab === 'register' }">
                        <form class="d-flex flex-column gap-2" method="POST" action="/register" ref="registerForm">
							<input-component 
								name="email" 
								placeholder="e-mail" 
								required 
								:error="errors['email']"
								@clear-error="clearError"
							/>
							<input-component 
								name="password" 
								type="password" 
								placeholder="Пароль" 
								required 
								:error="errors['password']"
								@clear-error="clearError"
							/>
							<input-component 
								name="password_confirmation" 
								type="password"
                                placeholder="Повторите пароль" 
								required 
								:error="errors['password-confirmation']"
								@clear-error="clearError"
							/>

                            <button class="btn btn-primary w-100 mt-12 mb-12" ref="registerButton"
                                @click.prevent="handleForm">Создать аккаунт</button>

                            <p class="text-light fs-8 w-100 text-center mb-0">
                                Нажимая кнопку “Создать аккаунт”, вы соглашаетесь с <a class="text-white"
								href="/policy">Условиями использования</a>.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
import InputComponent from "./../common/InputComponent.vue";
import Checkbox from "./../common/Checkbox.vue";

export default {
	data() {
        return {
            errors: {},
            notice: false,
            message: "",
			tab: null,
        };
    },
	props: {
		routeName: String,
	},
    mounted() {
        this.getTab();
    },
    methods: {
        getTab() {
            this.tab = this.routeName;
        },
        handleForm(event) {
            let form = event.target.closest("form");
            let data = new FormData(form);

            axios.post(form.action, data).then((response) => {
                window.location.replace('/');
            }).catch((response) => {
				console.log(response);
				this.handleErrors(response)
			});
        },
        handleErrors(response, message = null) {
            if (response.response?.data?.exception) {
                this.notify(message ?? response.response?.data?.message);
                return;
            }

            if (response.response?.data?.message) {
                const errors = response.response.data.errors;
                for (const key in errors) {
                    this.errors[key] = errors[key][0];
                }
                return;
            }
        },
		clearError(name) {
			this.errors[name] = null;
		}
    },
    watch: {
        tab() {
            setTimeout(() => {
                let tab = this.$refs.tabs.querySelector(".auth-tab_active");
                let left =
                    tab.getBoundingClientRect().left -
                    this.$refs.tabs.getBoundingClientRect().left;

                this.$refs.tabMarker.style.left = left + "px";
                this.$refs.tabMarker.style.width =
                    tab.getBoundingClientRect().width + "px";
            }, 50);
        },
    },
	components: {InputComponent, Checkbox},
}  
</script>

<style lang="scss" scoped></style>
