<template>
    <div>
        <div class="card">
            <div class="card-body pb-0">
                <form @submit.prevent="send">
                <div class="form-group">
                    <label class="required">Имя ребёнка</label>
                    <input class="form__input" v-model="child_name">
                </div>
                <div class="form-group">
                    <label class="required">Телефон родителя</label>
                    <input class="form__input" v-mask="'+## (###) ###-##-##'" placeholder="+__ (___) ___ __ __" v-model="phone">
                </div>
                    <b-form-group label="Родственная связь">
                        <b-form-radio-group
                            v-model="selected"
                            :options="options"
                            name="radio-inline"
                        ></b-form-radio-group>
                    </b-form-group>
                    <div class="form-group">
                        <label class="required">Филиал</label>
                        <input class="form__input" v-model="branch">
                    </div>
                <div class="form-group">
                    <label class="required">Дата</label>
                    <input class="form__input" type="date" min="2019-01-01" max="2030-01-01" v-model="date">
                </div>
                <div class="form-group">
                    <label class="required">Время</label>
                    <input class="form__input" type="time" min="00:01" max="23:59" v-model="time">
                </div>
                <div class="form-group">
                    <label>Заметка</label>
                    <textarea v-model="notes" class="form-control mb-3" rows="1"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Добавить</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>

    import VueToast from 'vue-toast-notification';
    import 'vue-toast-notification/dist/theme-sugar.css';
    Vue.use(VueToast);

    import Vuelidate from 'vuelidate'
    Vue.use(Vuelidate)
    import { required, minLength, between } from 'vuelidate/lib/validators'
    import Vue from "vue";

    export default {
        data() {
            return {
                selected: 'mother_lpr',
                options: [
                    { text: 'Мать', value: 'mother_lpr' },
                    { text: 'Отец', value: 'father_lpr' },
                    { text: 'Другой родственник', value: 'other_relative_lpr' }
                ],
                name: '',
                age: 0,
                child_name: '',
                phone: '',
                branch: '',
                date: '',
                time: '',
                notes: '',
            }
        },

        validations: {
            name: {
                required,
                minLength: minLength(4)
            },
            age: {
                between: between(20, 30)
            }
        },

        mounted() {

        },

        methods: {

            setName(value) {
                this.name = value
                this.$v.name.$touch()
            },
            setAge(value) {
                this.age = value
                this.$v.age.$touch()
            },

            send(){
                Vue.$toast.open({message: 'Нет занятий на этот день',type: 'info',duration: 5000,position: 'top-right'});
                axios.post( '/api/v2/addClientFromPromoter/', {
                    child_name: this.child_name,
                    phone: this.phone,
                    date: this.date,
                    time: this.time,
                    branch: this.branch,
                    notes: this.notes,
                    lpr: this.selected,
                })
            }
        },
    }
</script>


<style scoped>

    .form {
        position: relative; }

    .form__input,
    .form__textarea {
        position: relative;
        margin-bottom: 2rem; }

    .form__input,
    .form__textarea {
        font-family: "Lato", sans-serif;
        font-size: 0.875rem;
        font-weight: 300;
        color: #374853;
        line-height: 2.375rem;
        min-height: 2.375rem;
        position: relative;
        border: 1px solid #E8E8E8;
        border-radius: 5px;
        background: #fff;
        padding: 0 0.8125rem;
        width: 100%;
        transition: border .1s ease;
        box-sizing: border-box; }
    .form__input:hover,
    .form__textarea:hover {
        border-color: #cfcfcf; }
    .form__input:focus,
    .form__textarea:focus {
        border-color: #a8a8a8;
        outline: none; }
    .form__input--with-left-icon,
    .form__textarea--with-left-icon {
        padding-left: 2.8125rem; }
    .form__input--with-right-icon,
    .form__textarea--with-right-icon {
        padding-right: 2.8125rem; }

    .form__icon {
        position: absolute;
        pointer-events: none;
        top: -1px;
        height: 2.5rem;
        line-height: 2.5rem; }
    .form__icon--right {
        right: 0.9375rem; }
    .form__icon--left {
        left: 0.8125rem; }

    .form__textarea {
        max-width: 100%;
        min-height: 5.125rem;
        resize: none;
        line-height: 1.4;
        padding-top: 10px; }

    .form__label, .form__label--inline {
        font-size: 0.8125rem;
        color: #4b6372;
        margin-bottom: 0.3125rem;
        margin-left: 0.875rem;
        display: block;
        font-family: "Lato", sans-serif; }

    .form__label--inline {
        display: inline-block;
        margin-right: 1.25rem;
        margin-left: 0.5rem; }

    .form-group {
        margin-bottom: 2rem; }
    .form-group .form__input,
    .form-group .form__textarea {
        margin-bottom: 0; }

    .form-group--merged {
        font-size: 0;
        display: table;
        width: 100%;
        border-collapse: separate; }
    .form-group--merged .form__label, .form-group--merged .form__label--inline {
        display: table-caption; }

    .form-group__input,
    .form-group__addon,
    .form-group__button {
        display: table-cell;
        vertical-align: middle;
        margin: 0;
        white-space: nowrap; }

    .form-group__addon:first-child,
    .form-group__input:first-child,
    .form-group__button:first-child .button {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        margin-right: -1px; }

    .form-group__addon:last-child,
    .form-group__input:last-child,
    .form-group__button:last-child .button {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        margin-left: -1px; }

    .form-group__input:not(:first-child):not(:last-child),
    .form-group__addon:not(:first-child):not(:last-child),
    .form-group__button:not(:first-child):not(:last-child) .button {
        border-radius: 0; }

    .form-group__button:not(:first-child):not(:last-child) .button {
        margin-left: -1px;
        margin-right: -1px; }

    .form-group__addon:first-child {
        border-right: none; }

    .form-group__addon:last-child {
        border-left: none; }

    .form-group__addon {
        background: #F3F3F3;
        border: 1px solid #E8E8E8;
        border-radius: 5px;
        height: 2.375rem;
        line-height: 2.375rem;
        width: 1%;
        padding: 0 13px;
        font-size: 14px;
        text-align: center; }

    .form-group__button {
        width: 1%; }
    .form-group__button .button {
        margin: 0;
        padding-right: 1.25rem;
        padding-left: 1.25rem; }

    .form-group__message, .error {
        font-size: 0.75rem;
        line-height: 1;
        display: none;
        margin-left: 14px;
        margin-top: -1.6875rem;
        margin-bottom: 0.9375rem; }

    .form-group--alert,
    .form-group--error {
        animation-name: shakeError;
        animation-fill-mode: forwards;
        animation-duration: .6s;
        animation-timing-function: ease-in-out; }

    .form-group--loading .form__input {
        border-image-slice: 1;
        animation: loading-frame 1s infinite; }

    @keyframes loading-frame {
        0% {
            border-color: #3acfd5; }
        50% {
            border-color: #3a4ed5; }
        100% {
            border-color: #3acfd5; } }

    .form-group--success .form__label, .form-group--success .form__label--inline {
        color: #43AC6A; }

    .form-group--success .form-group__addon {
        color: white;
        border-color: #85d0a1;
        background: #85d0a1; }

    .form-group--success input,
    .form-group--success textarea,
    .form-group--success input:focus,
    .form-group--success input:hover {
        border-color: #85d0a1; }

    .form-group--success + .form-group__message, .form-group--success + .error {
        display: block;
        color: #73c893; }

    .form-group--error .form__label, .form-group--error .form__label--inline {
        color: #f04124; }

    .form-group--error .form-group__addon {
        color: white;
        border-color: #f79483;
        background: #f79483; }

    .form-group--error input,
    .form-group--error textarea,
    .form-group--error input:focus,
    .form-group--error input:hover {
        border-color: #f79483; }

    .form-group--error + .form-group__message, .form-group--error + .error {
        display: block;
        color: #f57f6c; }

    .form-group--alert .form__label, .form-group--alert .form__label--inline {
        color: #f08a24; }

    .form-group--alert .form-group__addon {
        color: white;
        border-color: #f7bd83;
        background: #f7bd83; }

    .form-group--alert input,
    .form-group--alert textarea,
    .form-group--alert input:focus,
    .form-group--alert input:hover {
        border-color: #f7bd83; }

    .form-group--alert + .form-group__message {
        display: block;
        color: #f5b06c; }

    @keyframes shakeError {
        0% {
            transform: translateX(0); }
        15% {
            transform: translateX(0.375rem); }
        30% {
            transform: translateX(-0.375rem); }
        45% {
            transform: translateX(0.375rem); }
        60% {
            transform: translateX(-0.375rem); }
        75% {
            transform: translateX(0.375rem); }
        90% {
            transform: translateX(-0.375rem); }
        100% {
            transform: translateX(0); } }


    </style>
