(self["webpackChunk"] = self["webpackChunk"] || []).push([["Registration"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LoginRegisterOutline.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LoginRegisterOutline.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ValidationError__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ValidationError */ "./resources/js/components/ValidationError.vue");
/* harmony import */ var vue_spinner_src_SyncLoader__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-spinner/src/SyncLoader */ "./node_modules/vue-spinner/src/SyncLoader.vue");
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    SyncLoader: vue_spinner_src_SyncLoader__WEBPACK_IMPORTED_MODULE_1__.default,
    ValidationError: _ValidationError__WEBPACK_IMPORTED_MODULE_0__.default
  },
  data: function data() {
    return {
      showErrorMessage: null,
      specialErrorMessage: null
    };
  },
  watch: {
    theErrorMessage: function theErrorMessage(newValue) {
      this.showErrorMessage = newValue;
    }
  },
  props: ['title', 'theErrorMessage'],
  methods: _objectSpread({
    issueClearValidation: function issueClearValidation() {
      this.specialErrorMessage = '';
      this.$emit('clear');
      this.clearValidation();
    }
  }, (0,vuex__WEBPACK_IMPORTED_MODULE_2__.mapActions)(['clearValidation'])),
  computed: _objectSpread(_objectSpread({
    currentLocation: function currentLocation() {
      return this.$route.name;
    },
    validationErrors: function validationErrors() {
      return this.specialErrorMessage ? this.specialErrorMessage : this.showErrorMessage ? this.showErrorMessage : ''; // 'You may have entered a wrong username or password'
    }
  }, (0,vuex__WEBPACK_IMPORTED_MODULE_2__.mapGetters)(['authenticating', 'getValidationErrors', 'authenticatingErrorMessage'])), {}, {
    showValidationErrors: function showValidationErrors() {
      if (!this.getValidationErrors) {
        return false;
      } else {
        return true;
      }
    },
    showAuthenticatingErrorMessage: function showAuthenticatingErrorMessage() {
      if (!this.showValidationErrors) {
        var errorMessage = this.authenticatingErrorMessage;
        var theErrorMessage = this.theErrorMessage;

        if (errorMessage && errorMessage.includes('Server Error')) {
          this.specialErrorMessage = 'The server may be down. Please try again in a few minutes. Apologizes';
          return true;
        } else if (errorMessage === 'Unauthorized') {
          if (this.$route.name === 'login') {
            this.specialErrorMessage = 'Please enter the correct username or email and password combination';
          }

          return true;
        } else if (errorMessage === 'Unauthenticated') {
          this.specialErrorMessage = 'Please you are unauthorized. Log in again';
          return true;
        } else if (errorMessage) {
          this.specialErrorMessage = 'Something broke somewhere. Please try again or alert us via a complaint.';
          return true;
        } else if (theErrorMessage) {
          this.showErrorMessage = theErrorMessage;
          return true;
        } else {
          return false;
        }
      }
    }
  }),
  created: function created() {
    this.showErrorMessage = this.theErrorMessage;
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ValidationError.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ValidationError.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    isString: {
      type: Boolean,
      "default": true
    },
    errorString: {
      type: String,
      "default": ''
    },
    errors: {
      type: Object,
      "default": function _default() {
        return {};
      }
    }
  },
  watch: {
    errors: {
      immediate: true,
      handler: function handler(newValue) {
        if (newValue.length > 0) {
          this.disappearSoon();
        }
      }
    },
    errorString: {
      immediate: true,
      handler: function handler(newValue) {
        if (newValue != '' || newValue != null) {
          this.disappearSoon();
        }
      }
    }
  },
  methods: {
    clearValidation: function clearValidation() {
      this.$emit('clearValidation');
    },
    disappearSoon: function disappearSoon() {
      setTimeout(function () {
        this.$emit('clearValidation');
      }.bind(this), 5000);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Registration.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Registration.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_LoginRegisterOutline__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/LoginRegisterOutline */ "./resources/js/components/LoginRegisterOutline.vue");
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
/* harmony import */ var vue_flatpickr_component__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-flatpickr-component */ "./node_modules/vue-flatpickr-component/dist/vue-flatpickr.min.js");
/* harmony import */ var vue_flatpickr_component__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(vue_flatpickr_component__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var flatpickr_dist_flatpickr_css__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! flatpickr/dist/flatpickr.css */ "./node_modules/flatpickr/dist/flatpickr.css");
/* harmony import */ var flatpickr_dist_flatpickr_css__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(flatpickr_dist_flatpickr_css__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _components_TextInput__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../components/TextInput */ "./resources/js/components/TextInput.vue");


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//





/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      username: '',
      email: '',
      password: '',
      passwordConfirmation: '',
      firstName: '',
      lastName: '',
      otherNames: '',
      registrationSection: null,
      sectionButtonText: 'next',
      dob: '',
      errorMessage: '',
      config: {},
      passwordTitle: 'show password',
      passwordConfirmationTitle: 'show password',
      passwordType: 'password',
      passwordConfirmationType: 'password',
      emailError: false,
      usernameError: false,
      passwordError: false,
      passwordConfirmationError: false,
      passwordIcon: ['fa', 'eye']
    };
  },
  components: {
    LoginRegisterOutline: _components_LoginRegisterOutline__WEBPACK_IMPORTED_MODULE_1__.default,
    TextInput: _components_TextInput__WEBPACK_IMPORTED_MODULE_4__.default,
    flatPickr: (vue_flatpickr_component__WEBPACK_IMPORTED_MODULE_2___default())
  },
  watch: {
    username: function username() {
      this.usernameError = false;
    },
    email: function email() {
      this.emailError = false;
    },
    password: function password() {
      this.passwordError = false;
      this.passwordConfirmationError = false;
    }
  },
  created: function created() {
    this.config = {
      maxDate: 'today',
      dateFormat: 'Y-m-d',
      altInput: true,
      altFormat: "F j, Y",
      defaultDate: ['1990-01-01']
    };
  },
  methods: _objectSpread(_objectSpread({
    clearErrorMessage: function clearErrorMessage() {
      this.errorMessage = '';
    },
    clearCredentials: function clearCredentials() {
      this.username = '';
      this.email = '';
      this.password = '';
      this.passwordConfirmation = '';
      this.firstName = '';
      this.lastName = '';
      this.otherNames = '';
      this.dod = '';
    },
    nextSection: function nextSection() {
      var registrationSection = this.registrationSection;
      this.errorMessage = '';

      if (registrationSection === null || registrationSection === 1) {
        if (this.username === '') {
          this.errorMessage = 'Please, enter your username in the field.';
          this.usernameError = true;
        } else if (this.username.length < 8) {
          this.errorMessage = 'Please, your username should have at least 8 characters.';
          this.usernameError = true;
        } else if (this.password === "") {
          this.errorMessage = 'Please, enter your password in the field.';
          this.passwordError = true;
        } else if (this.passwordConfirmation === "") {
          this.errorMessage = 'Please, confirm your password in the field.';
          this.passwordConfirmationError = true;
        } else if (this.password !== this.passwordConfirmation) {
          this.errorMessage = 'Please, password confirmation must match password.';
          this.passwordError = true;
          this.passwordConfirmationError = true;
        } else {
          this.registrationSection = 2;
          this.sectionButtonText = 'previous';
        }
      } else if (registrationSection === 2) {
        this.registrationSection = 1;
        this.sectionButtonText = 'next';
      }
    },
    sendRegistrationDetails: function sendRegistrationDetails() {
      var _this = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee() {
        var registrationCredentials, response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                registrationCredentials = {
                  username: _this.username.trim(),
                  email: _this.email.trim(),
                  password: _this.password.trim(),
                  password_confirmation: _this.passwordConfirmation.trim(),
                  first_name: _this.firstName.trim(),
                  last_name: _this.lastName.trim(),
                  other_names: _this.otherNames.trim(),
                  dob: _this.dob.trim()
                };
                _context.next = 3;
                return _this.register(registrationCredentials);

              case 3:
                response = _context.sent;

                if (response === 'successful') {
                  _this.clearCredentials();
                } else {}

              case 5:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    }
  }, (0,vuex__WEBPACK_IMPORTED_MODULE_5__.mapActions)(['register'])), {}, {
    passwordIconChange: function passwordIconChange() {
      if (this.passwordIcon[1] === 'eye') {
        this.passwordIcon[1] = 'eye-slash';
        this.passwordType = 'text';
        this.passwordConfirmationType = 'text';
        this.passwordTitle = 'hide password';
        this.passwordConfirmationTitle = 'hide password';
      } else {
        this.passwordIcon[1] = 'eye';
        this.passwordConfirmationType = 'password';
        this.passwordType = 'password';
        this.passwordConfirmationTitle = 'show password';
        this.passwordTitle = 'show password';
      }
    }
  })
});

/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LoginRegisterOutline.vue?vue&type=style&index=0&id=7913f361&lang=scss&scoped=true&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LoginRegisterOutline.vue?vue&type=style&index=0&id=7913f361&lang=scss&scoped=true& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, exports, __webpack_require__) => {

// Imports
var ___CSS_LOADER_API_IMPORT___ = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
exports = ___CSS_LOADER_API_IMPORT___(false);
// Module
exports.push([module.id, ".small-msg[data-v-7913f361] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.login[data-v-7913f361] {\n  display: block;\n  width: 60%;\n  margin: 40px auto 0;\n  background-color: cornsilk;\n  min-height: 70vh;\n  position: relative;\n}\n.login .other-link[data-v-7913f361] {\n  position: absolute;\n  bottom: -45px;\n  text-align: center;\n}\n.login .other-link div[data-v-7913f361] {\n  width: auto;\n  margin: 0 auto;\n  font-size: 14px;\n}\n.login .other-link div a[data-v-7913f361] {\n  text-decoration: none;\n  color: rgba(2, 104, 90, 0.6);\n  font-weight: 800;\n}\n.login .loading-state[data-v-7913f361] {\n  width: 40%;\n  text-align: center;\n  position: absolute;\n  top: -8%;\n  left: 30%;\n}\n.section-other[data-v-7913f361] {\n  transform: rotate(10deg);\n  background-color: rgba(22, 233, 205, 0.233);\n  height: 70vh;\n  width: 50%;\n  position: absolute;\n  display: flex;\n  justify-content: center;\n  align-items: center;\n}\n.section-other > div[data-v-7913f361]:first-child {\n  transform: rotate(-10deg);\n}\n.section-other .section-other-login[data-v-7913f361] {\n  font-size: 3vw;\n  font-weight: 800;\n  display: flex;\n}\n.section-main[data-v-7913f361] {\n  background-color: #16e9cd;\n  width: 60%;\n  min-height: 45.5vh;\n  border-radius: 0 2vw 2vw 0;\n  margin: 0 0 0 auto;\n  position: relative;\n  top: 15vh;\n  left: 5%;\n  display: flex;\n  justify-content: center;\n  align-items: center;\n}\n.section-main > div[data-v-7913f361]:first-child {\n  width: 80%;\n}\n.section-main .form-control[data-v-7913f361] {\n  margin: 0 !important;\n}\n.section-main .form-section[data-v-7913f361] {\n  width: 100%;\n  display: flex;\n  flex-wrap: wrap;\n  justify-content: space-between;\n  align-self: center;\n  margin-top: 10px;\n}\n.section-main .form-section label[data-v-7913f361] {\n  width: 100%;\n  color: rgba(255, 248, 220, 0.8);\n}\n.section-main .login-btn[data-v-7913f361] {\n  width: auto;\n  display: block;\n  margin: 0 auto;\n  background-color: rgba(2, 104, 90, 0.6);\n  border-radius: 10vh;\n  border: 1px solid #02685a;\n  color: rgba(255, 248, 220, 0.8);\n  transition-property: background-color;\n  transition-duration: 1s;\n  font-size: 3vw;\n}\n.section-main .login-btn[data-v-7913f361]:hover {\n  background-color: #02685a;\n}\n.section-main .login-with[data-v-7913f361] {\n  margin-top: 20px;\n  font-size: 2.5vw;\n}\n.section-main .login-type-btn[data-v-7913f361] {\n  color: #02685a;\n  border-radius: 10vh;\n  background-color: rgba(255, 248, 220, 0.8);\n  border: 1px solid rgba(255, 248, 220, 0.8);\n  transition-property: background-color;\n  transition-duration: 1s;\n  font-size: 3vw;\n}\n.section-main .login-type-btn[data-v-7913f361]:hover {\n  background-color: rgba(255, 248, 220, 0.8);\n}\n.section-main .register-datepicker-section .form-control .form-control[data-v-7913f361]:disabled, .section-main .register-datepicker-section .form-control .form-control[readonly][data-v-7913f361] {\n  border: 2px solid #16e9cd;\n  border-radius: 10px;\n  background-color: white;\n  opacity: 1;\n}\n.cursor-pointer[data-v-7913f361] {\n  cursor: pointer;\n}\n\n/* Extra small devices (phones, 600px and down) */\n@media only screen and (max-width: 500px) {\n.login .other-link[data-v-7913f361] {\n    bottom: -40px;\n}\n.login .validation .validation-errors[data-v-7913f361] {\n    font-size: 14px;\n}\n}\n/* Small devices (portrait tablets and large phones, 600px and up) */\n@media only screen and (min-width: 600px) {\n.section-main .login-btn[data-v-7913f361] {\n    font-size: 2vw;\n}\n.section-main .login-with[data-v-7913f361] {\n    font-size: 1.5vw;\n}\n.section-main .login-type-btn[data-v-7913f361] {\n    font-size: 2vw;\n}\n}\n/* Medium devices (landscape tablets, 768px and up) */\n@media only screen and (min-width: 768px) {\n.section-main .login-btn[data-v-7913f361] {\n    font-size: 1.5vw;\n}\n.section-main .login-with[data-v-7913f361] {\n    font-size: 1vw;\n}\n.section-main .login-type-btn[data-v-7913f361] {\n    font-size: 1.5vw;\n}\n}\n/* Large devices (laptops/desktops, 992px and up) */\n/* Extra large devices (large laptops and desktops, 1200px and up) */", ""]);
// Exports
module.exports = exports;


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ValidationError.vue?vue&type=style&index=0&id=7f8ae9f4&lang=scss&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ValidationError.vue?vue&type=style&index=0&id=7f8ae9f4&lang=scss&scoped=true& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, exports, __webpack_require__) => {

// Imports
var ___CSS_LOADER_API_IMPORT___ = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
exports = ___CSS_LOADER_API_IMPORT___(false);
// Module
exports.push([module.id, ".small-msg[data-v-7f8ae9f4] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.validation[data-v-7f8ae9f4] {\n  width: 100%;\n  padding: 5px;\n  background-color: rgba(252, 159, 159, 0.9);\n  font-weight: 700;\n  color: rgba(201, 6, 6, 0.9);\n  border: 1px solid rgba(201, 6, 6, 0.9);\n  display: flex;\n  justify-content: space-between;\n  align-items: center;\n  z-index: 2000;\n  border-radius: 10px;\n  font-size: 16px;\n}\n.validation .validation-errors[data-v-7f8ae9f4] {\n  padding: 5px;\n  font-size: 16px;\n}\n.validation .cursor-pointer[data-v-7f8ae9f4] {\n  cursor: pointer;\n  height: 100%;\n}\n@media screen and (max-width: 800px) {\n.validation[data-v-7f8ae9f4] {\n    font-size: 14px;\n}\n}", ""]);
// Exports
module.exports = exports;


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Registration.vue?vue&type=style&index=0&lang=scss&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Registration.vue?vue&type=style&index=0&lang=scss& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, exports, __webpack_require__) => {

// Imports
var ___CSS_LOADER_API_IMPORT___ = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
exports = ___CSS_LOADER_API_IMPORT___(false);
// Module
exports.push([module.id, ".small-msg {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.form-control:disabled, .form-control[readonly] {\n  border: 2px solid #16e9cd;\n  border-radius: 10px;\n  background-color: white;\n  opacity: 1;\n}", ""]);
// Exports
module.exports = exports;


/***/ }),

/***/ "./resources/js/components/LoginRegisterOutline.vue":
/*!**********************************************************!*\
  !*** ./resources/js/components/LoginRegisterOutline.vue ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _LoginRegisterOutline_vue_vue_type_template_id_7913f361_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LoginRegisterOutline.vue?vue&type=template&id=7913f361&scoped=true& */ "./resources/js/components/LoginRegisterOutline.vue?vue&type=template&id=7913f361&scoped=true&");
/* harmony import */ var _LoginRegisterOutline_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LoginRegisterOutline.vue?vue&type=script&lang=js& */ "./resources/js/components/LoginRegisterOutline.vue?vue&type=script&lang=js&");
/* harmony import */ var _LoginRegisterOutline_vue_vue_type_style_index_0_id_7913f361_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./LoginRegisterOutline.vue?vue&type=style&index=0&id=7913f361&lang=scss&scoped=true& */ "./resources/js/components/LoginRegisterOutline.vue?vue&type=style&index=0&id=7913f361&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__.default)(
  _LoginRegisterOutline_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _LoginRegisterOutline_vue_vue_type_template_id_7913f361_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _LoginRegisterOutline_vue_vue_type_template_id_7913f361_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "7913f361",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/LoginRegisterOutline.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/ValidationError.vue":
/*!*****************************************************!*\
  !*** ./resources/js/components/ValidationError.vue ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ValidationError_vue_vue_type_template_id_7f8ae9f4_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ValidationError.vue?vue&type=template&id=7f8ae9f4&scoped=true& */ "./resources/js/components/ValidationError.vue?vue&type=template&id=7f8ae9f4&scoped=true&");
/* harmony import */ var _ValidationError_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ValidationError.vue?vue&type=script&lang=js& */ "./resources/js/components/ValidationError.vue?vue&type=script&lang=js&");
/* harmony import */ var _ValidationError_vue_vue_type_style_index_0_id_7f8ae9f4_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ValidationError.vue?vue&type=style&index=0&id=7f8ae9f4&lang=scss&scoped=true& */ "./resources/js/components/ValidationError.vue?vue&type=style&index=0&id=7f8ae9f4&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__.default)(
  _ValidationError_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _ValidationError_vue_vue_type_template_id_7f8ae9f4_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _ValidationError_vue_vue_type_template_id_7f8ae9f4_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "7f8ae9f4",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/ValidationError.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/views/Registration.vue":
/*!*********************************************!*\
  !*** ./resources/js/views/Registration.vue ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Registration_vue_vue_type_template_id_111cccd8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Registration.vue?vue&type=template&id=111cccd8& */ "./resources/js/views/Registration.vue?vue&type=template&id=111cccd8&");
/* harmony import */ var _Registration_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Registration.vue?vue&type=script&lang=js& */ "./resources/js/views/Registration.vue?vue&type=script&lang=js&");
/* harmony import */ var _Registration_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Registration.vue?vue&type=style&index=0&lang=scss& */ "./resources/js/views/Registration.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__.default)(
  _Registration_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _Registration_vue_vue_type_template_id_111cccd8___WEBPACK_IMPORTED_MODULE_0__.render,
  _Registration_vue_vue_type_template_id_111cccd8___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/Registration.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/LoginRegisterOutline.vue?vue&type=script&lang=js&":
/*!***********************************************************************************!*\
  !*** ./resources/js/components/LoginRegisterOutline.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginRegisterOutline_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LoginRegisterOutline.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LoginRegisterOutline.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginRegisterOutline_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/ValidationError.vue?vue&type=script&lang=js&":
/*!******************************************************************************!*\
  !*** ./resources/js/components/ValidationError.vue?vue&type=script&lang=js& ***!
  \******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ValidationError_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ValidationError.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ValidationError.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ValidationError_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/views/Registration.vue?vue&type=script&lang=js&":
/*!**********************************************************************!*\
  !*** ./resources/js/views/Registration.vue?vue&type=script&lang=js& ***!
  \**********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Registration_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Registration.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Registration.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Registration_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/LoginRegisterOutline.vue?vue&type=template&id=7913f361&scoped=true&":
/*!*****************************************************************************************************!*\
  !*** ./resources/js/components/LoginRegisterOutline.vue?vue&type=template&id=7913f361&scoped=true& ***!
  \*****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginRegisterOutline_vue_vue_type_template_id_7913f361_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginRegisterOutline_vue_vue_type_template_id_7913f361_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginRegisterOutline_vue_vue_type_template_id_7913f361_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LoginRegisterOutline.vue?vue&type=template&id=7913f361&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LoginRegisterOutline.vue?vue&type=template&id=7913f361&scoped=true&");


/***/ }),

/***/ "./resources/js/components/ValidationError.vue?vue&type=template&id=7f8ae9f4&scoped=true&":
/*!************************************************************************************************!*\
  !*** ./resources/js/components/ValidationError.vue?vue&type=template&id=7f8ae9f4&scoped=true& ***!
  \************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ValidationError_vue_vue_type_template_id_7f8ae9f4_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ValidationError_vue_vue_type_template_id_7f8ae9f4_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ValidationError_vue_vue_type_template_id_7f8ae9f4_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ValidationError.vue?vue&type=template&id=7f8ae9f4&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ValidationError.vue?vue&type=template&id=7f8ae9f4&scoped=true&");


/***/ }),

/***/ "./resources/js/views/Registration.vue?vue&type=template&id=111cccd8&":
/*!****************************************************************************!*\
  !*** ./resources/js/views/Registration.vue?vue&type=template&id=111cccd8& ***!
  \****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Registration_vue_vue_type_template_id_111cccd8___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Registration_vue_vue_type_template_id_111cccd8___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Registration_vue_vue_type_template_id_111cccd8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Registration.vue?vue&type=template&id=111cccd8& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Registration.vue?vue&type=template&id=111cccd8&");


/***/ }),

/***/ "./resources/js/components/LoginRegisterOutline.vue?vue&type=style&index=0&id=7913f361&lang=scss&scoped=true&":
/*!********************************************************************************************************************!*\
  !*** ./resources/js/components/LoginRegisterOutline.vue?vue&type=style&index=0&id=7913f361&lang=scss&scoped=true& ***!
  \********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginRegisterOutline_vue_vue_type_style_index_0_id_7913f361_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-style-loader/index.js!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LoginRegisterOutline.vue?vue&type=style&index=0&id=7913f361&lang=scss&scoped=true& */ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LoginRegisterOutline.vue?vue&type=style&index=0&id=7913f361&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginRegisterOutline_vue_vue_type_style_index_0_id_7913f361_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginRegisterOutline_vue_vue_type_style_index_0_id_7913f361_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ var __WEBPACK_REEXPORT_OBJECT__ = {};
/* harmony reexport (unknown) */ for(const __WEBPACK_IMPORT_KEY__ in _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginRegisterOutline_vue_vue_type_style_index_0_id_7913f361_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== "default") __WEBPACK_REEXPORT_OBJECT__[__WEBPACK_IMPORT_KEY__] = () => _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginRegisterOutline_vue_vue_type_style_index_0_id_7913f361_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__[__WEBPACK_IMPORT_KEY__]
/* harmony reexport (unknown) */ __webpack_require__.d(__webpack_exports__, __WEBPACK_REEXPORT_OBJECT__);


/***/ }),

/***/ "./resources/js/components/ValidationError.vue?vue&type=style&index=0&id=7f8ae9f4&lang=scss&scoped=true&":
/*!***************************************************************************************************************!*\
  !*** ./resources/js/components/ValidationError.vue?vue&type=style&index=0&id=7f8ae9f4&lang=scss&scoped=true& ***!
  \***************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_ValidationError_vue_vue_type_style_index_0_id_7f8ae9f4_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-style-loader/index.js!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ValidationError.vue?vue&type=style&index=0&id=7f8ae9f4&lang=scss&scoped=true& */ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ValidationError.vue?vue&type=style&index=0&id=7f8ae9f4&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_ValidationError_vue_vue_type_style_index_0_id_7f8ae9f4_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_ValidationError_vue_vue_type_style_index_0_id_7f8ae9f4_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ var __WEBPACK_REEXPORT_OBJECT__ = {};
/* harmony reexport (unknown) */ for(const __WEBPACK_IMPORT_KEY__ in _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_ValidationError_vue_vue_type_style_index_0_id_7f8ae9f4_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== "default") __WEBPACK_REEXPORT_OBJECT__[__WEBPACK_IMPORT_KEY__] = () => _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_ValidationError_vue_vue_type_style_index_0_id_7f8ae9f4_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__[__WEBPACK_IMPORT_KEY__]
/* harmony reexport (unknown) */ __webpack_require__.d(__webpack_exports__, __WEBPACK_REEXPORT_OBJECT__);


/***/ }),

/***/ "./resources/js/views/Registration.vue?vue&type=style&index=0&lang=scss&":
/*!*******************************************************************************!*\
  !*** ./resources/js/views/Registration.vue?vue&type=style&index=0&lang=scss& ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_Registration_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-style-loader/index.js!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Registration.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Registration.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_Registration_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_Registration_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ var __WEBPACK_REEXPORT_OBJECT__ = {};
/* harmony reexport (unknown) */ for(const __WEBPACK_IMPORT_KEY__ in _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_Registration_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== "default") __WEBPACK_REEXPORT_OBJECT__[__WEBPACK_IMPORT_KEY__] = () => _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_Registration_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[__WEBPACK_IMPORT_KEY__]
/* harmony reexport (unknown) */ __webpack_require__.d(__webpack_exports__, __WEBPACK_REEXPORT_OBJECT__);


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LoginRegisterOutline.vue?vue&type=template&id=7913f361&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LoginRegisterOutline.vue?vue&type=template&id=7913f361&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c(
      "div",
      { staticClass: "login" },
      [
        _c(
          "div",
          { staticClass: "loading-state" },
          [_c("sync-loader", { attrs: { loading: _vm.authenticating } })],
          1
        ),
        _vm._v(" "),
        _vm.showAuthenticatingErrorMessage
          ? _c("validation-error", {
              staticClass: "validation",
              attrs: { errorString: _vm.validationErrors },
              on: {
                clearValidation: function($event) {
                  return _vm.issueClearValidation()
                }
              }
            })
          : _vm._e(),
        _vm._v(" "),
        _vm.showValidationErrors
          ? _c("validation-error", {
              staticClass: "validation",
              attrs: { isString: false, errors: _vm.getValidationErrors },
              on: {
                clearValidation: function($event) {
                  return _vm.issueClearValidation()
                }
              }
            })
          : _vm._e(),
        _vm._v(" "),
        _c("div", { staticClass: "section-other" }, [
          _c("div", [
            _c("div", { staticClass: "section-other-login" }, [
              _c("span", { staticClass: "mr-2" }, [_vm._t("title-icon")], 2),
              _vm._v(" "),
              _c("span", [
                _vm._v(
                  "\n                        " +
                    _vm._s(_vm.title) +
                    "\n                    "
                )
              ])
            ]),
            _vm._v("\n\n                LOGO\n            ")
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "section-main py-3" }, [
          _c("div", {}, [
            _c(
              "form",
              {
                staticClass: "mt-3 mb2",
                on: {
                  submit: function($event) {
                    $event.preventDefault()
                  }
                }
              },
              [_vm._t("login-controls")],
              2
            )
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "other-link" }, [
            _vm.currentLocation === "login"
              ? _c(
                  "div",
                  [
                    _vm._v(
                      "\n                    if you have not registered, \n                    "
                    ),
                    _c("router-link", { attrs: { to: "/register" } }, [
                      _vm._v("register")
                    ])
                  ],
                  1
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.currentLocation === "register"
              ? _c(
                  "div",
                  [
                    _vm._v(
                      "\n                    if you have already registered, \n                    "
                    ),
                    _c("router-link", { attrs: { to: "/login" } }, [
                      _vm._v("login")
                    ])
                  ],
                  1
                )
              : _vm._e(),
            _vm._v(" "),
            _c(
              "div",
              [
                _vm._v("\n                    go \n                    "),
                _c("router-link", { attrs: { to: "/" } }, [_vm._v("home")])
              ],
              1
            )
          ])
        ])
      ],
      1
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ValidationError.vue?vue&type=template&id=7f8ae9f4&scoped=true&":
/*!***************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ValidationError.vue?vue&type=template&id=7f8ae9f4&scoped=true& ***!
  \***************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "validation" }, [
    _c(
      "div",
      [
        _vm.isString
          ? _c("div", [
              _vm._v("\n            " + _vm._s(_vm.errorString) + "\n        ")
            ])
          : _vm._l(_vm.errors, function(error, key) {
              return _c("div", { key: key }, [
                _c("div", { staticClass: "validation-errors" }, [
                  _vm._v(
                    "\n                " +
                      _vm._s(error.toString()) +
                      "\n            "
                  )
                ])
              ])
            })
      ],
      2
    ),
    _vm._v(" "),
    _c(
      "div",
      { staticClass: "cursor-pointer", on: { click: _vm.clearValidation } },
      [_c("font-awesome-icon", { attrs: { icon: ["fas", "times"] } })],
      1
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Registration.vue?vue&type=template&id=111cccd8&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Registration.vue?vue&type=template&id=111cccd8& ***!
  \*******************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _c("app-nav"),
      _vm._v(" "),
      _c("login-register-outline", {
        attrs: { title: "Register", theErrorMessage: _vm.errorMessage },
        on: { clear: _vm.clearErrorMessage },
        scopedSlots: _vm._u([
          {
            key: "login-controls",
            fn: function() {
              return [
                _c(
                  "div",
                  {
                    directives: [
                      {
                        name: "show",
                        rawName: "v-show",
                        value:
                          _vm.registrationSection === null ||
                          _vm.registrationSection === 1,
                        expression:
                          "registrationSection===null || registrationSection===1"
                      }
                    ],
                    staticClass: "section-one"
                  },
                  [
                    _c(
                      "div",
                      { staticClass: "form-group form-section text" },
                      [
                        _c("label", { attrs: { for: "login-username" } }, [
                          _vm._v("username *")
                        ]),
                        _vm._v(" "),
                        _c("text-input", {
                          attrs: {
                            placeholder: "your username",
                            error: _vm.usernameError
                          },
                          model: {
                            value: _vm.username,
                            callback: function($$v) {
                              _vm.username = $$v
                            },
                            expression: "username"
                          }
                        })
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "form-group form-section" },
                      [
                        _c("label", { attrs: { for: "login-email" } }, [
                          _vm._v("email")
                        ]),
                        _vm._v(" "),
                        _c("text-input", {
                          attrs: {
                            placeholder: "your email",
                            error: _vm.emailError
                          },
                          model: {
                            value: _vm.email,
                            callback: function($$v) {
                              _vm.email = $$v
                            },
                            expression: "email"
                          }
                        })
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "form-group form-section" },
                      [
                        _c("label", { attrs: { for: "login-password" } }, [
                          _vm._v("password *")
                        ]),
                        _vm._v(" "),
                        _c("text-input", {
                          attrs: {
                            placeholder: "your password",
                            textInput: _vm.passwordType,
                            error: _vm.passwordError,
                            title: _vm.passwordTitle,
                            icon: _vm.passwordIcon,
                            prepend: true
                          },
                          on: { iconChange: _vm.passwordIconChange },
                          model: {
                            value: _vm.password,
                            callback: function($$v) {
                              _vm.password = $$v
                            },
                            expression: "password"
                          }
                        })
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "form-group form-section" },
                      [
                        _c("label", { attrs: { for: "login-confirmation" } }, [
                          _vm._v("password confirmation *")
                        ]),
                        _vm._v(" "),
                        _c("text-input", {
                          attrs: {
                            placeholder: "your password confirmation",
                            textInput: _vm.passwordConfirmationType,
                            error: _vm.passwordConfirmationError,
                            title: _vm.passwordConfirmationTitle,
                            icon: _vm.passwordIcon,
                            prepend: true
                          },
                          on: { iconChange: _vm.passwordIconChange },
                          model: {
                            value: _vm.passwordConfirmation,
                            callback: function($$v) {
                              _vm.passwordConfirmation = $$v
                            },
                            expression: "passwordConfirmation"
                          }
                        })
                      ],
                      1
                    )
                  ]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  {
                    directives: [
                      {
                        name: "show",
                        rawName: "v-show",
                        value: _vm.registrationSection === 2,
                        expression: "registrationSection===2"
                      }
                    ],
                    staticClass: "section-two"
                  },
                  [
                    _c(
                      "div",
                      { staticClass: "form-group form-section" },
                      [
                        _c("label", { attrs: { for: "login-first-name" } }, [
                          _vm._v("first name")
                        ]),
                        _vm._v(" "),
                        _c("text-input", {
                          attrs: { placeholder: "your first name" },
                          model: {
                            value: _vm.firstName,
                            callback: function($$v) {
                              _vm.firstName = $$v
                            },
                            expression: "firstName"
                          }
                        })
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "form-group form-section" },
                      [
                        _c("label", { attrs: { for: "login-last-name" } }, [
                          _vm._v("last name")
                        ]),
                        _vm._v(" "),
                        _c("text-input", {
                          attrs: { placeholder: "your last name" },
                          model: {
                            value: _vm.lastName,
                            callback: function($$v) {
                              _vm.lastName = $$v
                            },
                            expression: "lastName"
                          }
                        })
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "form-group form-section" },
                      [
                        _c("label", { attrs: { for: "login-other-names" } }, [
                          _vm._v("other names")
                        ]),
                        _vm._v(" "),
                        _c("text-input", {
                          attrs: { placeholder: "your other names" },
                          model: {
                            value: _vm.otherNames,
                            callback: function($$v) {
                              _vm.otherNames = $$v
                            },
                            expression: "otherNames"
                          }
                        })
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      {
                        staticClass:
                          "form-group form-section register-datepicker-section"
                      },
                      [
                        _c("label", { attrs: { for: "register-datepicker" } }, [
                          _vm._v("date of birth")
                        ]),
                        _vm._v(" "),
                        _c("flat-pickr", {
                          staticClass: " form-control mb-2",
                          attrs: {
                            id: "register-datepicker",
                            config: _vm.config
                          },
                          model: {
                            value: _vm.dob,
                            callback: function($$v) {
                              _vm.dob = $$v
                            },
                            expression: "dob"
                          }
                        })
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c(
                      "button",
                      {
                        staticClass: "btn login-btn mb-3",
                        on: {
                          click: function($event) {
                            $event.preventDefault()
                            return _vm.sendRegistrationDetails($event)
                          }
                        }
                      },
                      [_vm._v("register")]
                    )
                  ]
                ),
                _vm._v(" "),
                _c(
                  "button",
                  {
                    staticClass: "btn login-btn",
                    on: {
                      click: function($event) {
                        $event.preventDefault()
                        return _vm.nextSection($event)
                      }
                    }
                  },
                  [_vm._v(_vm._s(_vm.sectionButtonText))]
                )
              ]
            },
            proxy: true
          },
          {
            key: "title-icon",
            fn: function() {
              return [
                _c("font-awesome-icon", {
                  attrs: { icon: ["fas", "sign-in-alt"] }
                })
              ]
            },
            proxy: true
          }
        ])
      })
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LoginRegisterOutline.vue?vue&type=style&index=0&id=7913f361&lang=scss&scoped=true&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LoginRegisterOutline.vue?vue&type=style&index=0&id=7913f361&lang=scss&scoped=true& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(/*! !!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LoginRegisterOutline.vue?vue&type=style&index=0&id=7913f361&lang=scss&scoped=true& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LoginRegisterOutline.vue?vue&type=style&index=0&id=7913f361&lang=scss&scoped=true&");
if(content.__esModule) content = content.default;
if(typeof content === 'string') content = [[module.id, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var add = __webpack_require__(/*! !../../../node_modules/vue-style-loader/lib/addStylesClient.js */ "./node_modules/vue-style-loader/lib/addStylesClient.js").default
var update = add("1f52f5c8", content, false, {});
// Hot Module Replacement
if(false) {}

/***/ }),

/***/ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ValidationError.vue?vue&type=style&index=0&id=7f8ae9f4&lang=scss&scoped=true&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ValidationError.vue?vue&type=style&index=0&id=7f8ae9f4&lang=scss&scoped=true& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(/*! !!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ValidationError.vue?vue&type=style&index=0&id=7f8ae9f4&lang=scss&scoped=true& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ValidationError.vue?vue&type=style&index=0&id=7f8ae9f4&lang=scss&scoped=true&");
if(content.__esModule) content = content.default;
if(typeof content === 'string') content = [[module.id, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var add = __webpack_require__(/*! !../../../node_modules/vue-style-loader/lib/addStylesClient.js */ "./node_modules/vue-style-loader/lib/addStylesClient.js").default
var update = add("20f651e6", content, false, {});
// Hot Module Replacement
if(false) {}

/***/ }),

/***/ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Registration.vue?vue&type=style&index=0&lang=scss&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Registration.vue?vue&type=style&index=0&lang=scss& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(/*! !!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Registration.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Registration.vue?vue&type=style&index=0&lang=scss&");
if(content.__esModule) content = content.default;
if(typeof content === 'string') content = [[module.id, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var add = __webpack_require__(/*! !../../../node_modules/vue-style-loader/lib/addStylesClient.js */ "./node_modules/vue-style-loader/lib/addStylesClient.js").default
var update = add("e18461c6", content, false, {});
// Hot Module Replacement
if(false) {}

/***/ })

}]);