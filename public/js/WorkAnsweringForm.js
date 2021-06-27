(self["webpackChunk"] = self["webpackChunk"] || []).push([["WorkAnsweringForm"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************************/
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    value: {
      type: Boolean,
      "default": false
    },
    label: {
      type: String,
      "default": ''
    },
    small: {
      type: Boolean,
      "default": false
    }
  },
  data: function data() {
    return {
      inputValue: false,
      randomId: ''
    };
  },
  watch: {
    inputValue: function inputValue(newValue) {
      this.$refs.checkboxicon.classList.toggle('icon');
      this.$emit('input', newValue);
    },
    value: {
      immediate: true,
      handler: function handler(newValue) {
        this.inputValue = newValue;
      }
    }
  },
  mounted: function mounted() {
    this.randomId = "maincheckbox".concat(Math.floor(Math.random() * 1000));
  },
  methods: {
    toggleValue: function toggleValue() {
      this.inputValue = !this.inputValue;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _QuestionAnsweringBadge__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./QuestionAnsweringBadge */ "./resources/js/components/dashboard/QuestionAnsweringBadge.vue");
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
    QuestionAnsweringBadge: _QuestionAnsweringBadge__WEBPACK_IMPORTED_MODULE_0__.default
  },
  props: {
    assessmentSection: {
      type: Object,
      "default": function _default() {
        return null;
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _specials_DraggableComponent__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../specials/DraggableComponent */ "./resources/js/components/specials/DraggableComponent.vue");
/* harmony import */ var _specials_DroppableComponent__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../specials/DroppableComponent */ "./resources/js/components/specials/DroppableComponent.vue");
/* harmony import */ var _MainCheckbox__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../MainCheckbox */ "./resources/js/components/MainCheckbox.vue");
/* harmony import */ var _services_helpers__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../services/helpers */ "./resources/js/services/helpers.js");
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
  components: {
    DroppableComponent: _specials_DroppableComponent__WEBPACK_IMPORTED_MODULE_1__.default,
    DraggableComponent: _specials_DraggableComponent__WEBPACK_IMPORTED_MODULE_0__.default,
    MainCheckbox: _MainCheckbox__WEBPACK_IMPORTED_MODULE_2__.default
  },
  props: {
    possibleAnswer: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    correctPossibleAnswers: {
      type: Array,
      "default": function _default() {
        return [];
      }
    },
    autoMark: {
      type: Boolean,
      "default": false
    },
    answering: {
      type: Boolean,
      "default": false
    },
    drag: {
      type: Boolean,
      "default": true
    },
    removed: {
      type: Boolean,
      "default": false
    },
    answerType: {
      type: String,
      "default": ''
    },
    possibleAnswerIndex: {
      type: String | Number,
      "default": null
    },
    possibleAnswersLength: {
      type: String | Number,
      "default": null
    }
  },
  data: function data() {
    return {
      correct: false
    };
  },
  watch: {
    correct: function correct(newValue) {
      if (newValue) {
        this.$emit('possibleAnswerIsCorrect', this.possibleAnswer);
      } else {
        this.$emit('possibleAnswerIsWrong', this.possibleAnswer);
      }
    },
    correctPossibleAnswers: function correctPossibleAnswers(newValue) {
      if (!(this.computedTrueOrFalse || this.computedOption)) {
        return;
      }

      this.correct = false;

      if (!newValue.length) {
        return;
      }

      if (this.isCorrectTrueOrFalse(newValue) || this.isCorrectOption(newValue)) {
        this.correct = true;
      }
    }
  },
  computed: {
    computedTrueOrFalse: function computedTrueOrFalse() {
      return this.answerType.toLowerCase().includes('true');
    },
    computedOption: function computedOption() {
      return this.answerType.toLowerCase().includes('option');
    },
    computedArrange: function computedArrange() {
      return this.answerType.toLowerCase().includes('arrange');
    },
    computedFlow: function computedFlow() {
      return this.answerType.toLowerCase().includes('flow');
    },
    computedPosition: function computedPosition() {
      return _services_helpers__WEBPACK_IMPORTED_MODULE_3__.strings.getNumberLetter(this.possibleAnswer.position);
    }
  },
  methods: {
    movePossibleAnswer: function movePossibleAnswer(data) {
      this.$emit('movePossibleAnswer', data);
    },
    editPossibleAnswer: function editPossibleAnswer() {
      this.$emit('editPossibleAnswer', this.possibleAnswer);
    },
    isCorrectTrueOrFalse: function isCorrectTrueOrFalse(data) {
      return this.computedTrueOrFalse && data[0].option === this.possibleAnswer.option;
    },
    isCorrectOption: function isCorrectOption(data) {
      return this.computedOption && data[0].id === this.possibleAnswer.id;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionAnsweringBadge.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionAnsweringBadge.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _forms_AnswerForm__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../forms/AnswerForm */ "./resources/js/components/forms/AnswerForm.vue");
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
    AnswerForm: _forms_AnswerForm__WEBPACK_IMPORTED_MODULE_0__.default
  },
  props: {
    question: {
      type: Object,
      "default": function _default() {
        return null;
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AnswerForm.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AnswerForm.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _TextTextarea_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../TextTextarea.vue */ "./resources/js/components/TextTextarea.vue");
/* harmony import */ var _TextInput_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../TextInput.vue */ "./resources/js/components/TextInput.vue");
/* harmony import */ var _dashboard_PossibleAnswerBadge_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../dashboard/PossibleAnswerBadge.vue */ "./resources/js/components/dashboard/PossibleAnswerBadge.vue");
/* harmony import */ var _specials_DroppableComponent_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../specials/DroppableComponent.vue */ "./resources/js/components/specials/DroppableComponent.vue");
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
    DroppableComponent: _specials_DroppableComponent_vue__WEBPACK_IMPORTED_MODULE_3__.default,
    PossibleAnswerBadge: _dashboard_PossibleAnswerBadge_vue__WEBPACK_IMPORTED_MODULE_2__.default,
    TextInput: _TextInput_vue__WEBPACK_IMPORTED_MODULE_1__.default,
    TextTextarea: _TextTextarea_vue__WEBPACK_IMPORTED_MODULE_0__.default
  },
  props: {
    answerType: {
      type: String,
      "default": null
    },
    possibleAnswers: {
      type: Array,
      "default": function _default() {
        return [];
      }
    }
  },
  data: function data() {
    return {
      answer: {
        answer: '',
        file: null,
        possibleAnswerIds: []
      }
    };
  },
  computed: {
    computedDrag: function computedDrag() {
      return ['arrange', 'flow'].includes(this.computedAnswerType);
    },
    computedAnswerType: function computedAnswerType() {
      return this.answerType ? this.answerType.toLowerCase() : '';
    }
  },
  methods: {
    movePossibleAnswer: function movePossibleAnswer() {},
    possibleAnswerIsCorrect: function possibleAnswerIsCorrect() {},
    possibleAnswerIsWrong: function possibleAnswerIsWrong() {}
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _dashboard_AssessmentSectionAnsweringBadge__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../dashboard/AssessmentSectionAnsweringBadge */ "./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue");
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
    AssessmentSectionAnsweringBadge: _dashboard_AssessmentSectionAnsweringBadge__WEBPACK_IMPORTED_MODULE_0__.default
  },
  props: {
    assessment: {
      type: Object,
      "default": function _default() {
        return null;
      }
    }
  },
  watch: {
    assessment: {
      immediate: true,
      handler: function handler(newValue, oldValue) {
        if (!newValue) {
          return;
        }

        if (!newValue.assessmentSections.length) {
          return;
        }

        this.currentAssessmentSection = newValue.assessmentSections[0];
        this.firstAssessmentSectionId = newValue.assessmentSections[0].id;
        this.lastAssessmentSectionId = newValue.assessmentSections[newValue.assessmentSections.length - 1].id;
      }
    }
  },
  data: function data() {
    return {
      currentAssessmentSection: null,
      firstAssessmentSectionId: null,
      lastAssessmentSectionId: null
    };
  },
  computed: {
    computedCurrentAssessmentSectionIndex: function computedCurrentAssessmentSectionIndex() {
      return this.assessment.assessmentSections.indexOf(this.currentAssessmentSection);
    }
  },
  methods: {
    clickedSectionNavigator: function clickedSectionNavigator(text) {
      if (text === 'next') {
        this.goToNext();
        return;
      }

      this.goToPrevious();
    },
    goTo: function goTo(number) {
      this.currentAssessmentSection = this.assessment.assessmentSections[this.computedCurrentAssessmentSectionIndex + number];
    },
    goToNext: function goToNext() {
      this.goTo(1);
    },
    goToPrevious: function goToPrevious() {
      this.goTo(-1);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _AssessmentSectionAnsweringForm__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AssessmentSectionAnsweringForm */ "./resources/js/components/forms/AssessmentSectionAnsweringForm.vue");
/* harmony import */ var vue_spinner_src_PulseLoader__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-spinner/src/PulseLoader */ "./node_modules/vue-spinner/src/PulseLoader.vue");
/* harmony import */ var _mixins_Alert_mixin__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../mixins/Alert.mixin */ "./resources/js/mixins/Alert.mixin.js");
/* harmony import */ var _PostButton__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../PostButton */ "./resources/js/components/PostButton.vue");
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");


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





/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    AssessmentSectionAnsweringForm: _AssessmentSectionAnsweringForm__WEBPACK_IMPORTED_MODULE_1__.default,
    PulseLoader: vue_spinner_src_PulseLoader__WEBPACK_IMPORTED_MODULE_2__.default,
    PostButton: _PostButton__WEBPACK_IMPORTED_MODULE_4__.default
  },
  mixins: [_mixins_Alert_mixin__WEBPACK_IMPORTED_MODULE_3__.default],
  props: {
    show: {
      type: Boolean,
      "default": true
    }
  },
  data: function data() {
    return {
      buttonText: 'create',
      loading: false,
      mainLoading: false,
      assessment: null,
      work: null,
      noDataMessage: ''
    };
  },
  computed: _objectSpread(_objectSpread({}, (0,vuex__WEBPACK_IMPORTED_MODULE_5__.mapGetters)(['getUser'])), {}, {
    computedAnswered: function computedAnswered() {
      return false;
    },
    computedHasNoData: function computedHasNoData() {
      return this.noDataMessage.length;
    }
  }),
  mounted: function mounted() {
    this.getWork();
  },
  methods: _objectSpread(_objectSpread({}, (0,vuex__WEBPACK_IMPORTED_MODULE_5__.mapActions)(['dashboard/getWork'])), {}, {
    closeModal: function closeModal() {
      this.$emit('closeWorkAnsweringForm');

      if (this.$route.fullPath.includes('dashboard')) {
        this.$router.push({
          name: 'dashboard'
        });
      }

      if (this.$route.fullPath.includes('profile')) {
        this.$router.push({
          name: 'profile'
        });
      }

      if (this.$route.fullPath.includes('/work')) {
        this.$router.push({
          name: 'home'
        });
      }
    },
    getWork: function getWork() {
      var _this = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee() {
        var data, response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                data = {
                  assessmentId: _this.$route.params.assessmentId
                };
                _this.mainLoading = true;
                _context.next = 4;
                return _this['dashboard/getWork'](data);

              case 4:
                response = _context.sent;
                _this.mainLoading = false;

                if (response.status) {
                  _this.assessment = response.assessment;
                } else {
                  console.log('response :>> ', response);
                }

              case 7:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },
    clickedCreate: function clickedCreate() {}
  })
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/DraggableComponent.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/DraggableComponent.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************************/
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    dataTransfer: {
      type: Object,
      "default": function _default() {
        return {};
      }
    },
    drag: {
      type: Boolean,
      "default": true
    },
    effectAllowed: {
      type: String,
      "default": 'move'
    },
    dropEffect: {
      type: String,
      "default": 'move'
    }
  },
  methods: {
    dragStart: function dragStart(event) {
      if (!this.drag) {
        return;
      }

      event.dataTransfer.effectAllowed = this.effectAllowed;
      event.dataTransfer.dropEffect = this.dropEffect;
      event.dataTransfer.setData('data', JSON.stringify(this.dataTransfer));
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/DroppableComponent.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/DroppableComponent.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    dataTransfer: {
      type: Object,
      "default": function _default() {
        return {};
      }
    }
  },
  methods: {
    drop: function drop(event) {
      if (!event.dataTransfer.getData('data')) {
        return;
      }

      var data = _objectSpread(_objectSpread({}, JSON.parse(event.dataTransfer.getData('data'))), this.dataTransfer);

      this.$emit('drop', data);
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, exports, __webpack_require__) => {

// Imports
var ___CSS_LOADER_API_IMPORT___ = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
exports = ___CSS_LOADER_API_IMPORT___(false);
// Module
exports.push([module.id, ".small-msg[data-v-49938287] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.main-checkbox-wrapper[data-v-49938287] {\n  display: flex;\n  align-items: center;\n  padding: 5px;\n  cursor: pointer;\n}\n.main-checkbox-wrapper .no-icon[data-v-49938287] {\n  width: 20px;\n  height: 20px;\n  border: 2px solid #16e9cd;\n  margin-right: 10px;\n  border-radius: 50%;\n  display: flex;\n  align-items: center;\n  justify-content: center;\n  padding: 10px;\n  font-size: 14px;\n  color: white;\n}\n.main-checkbox-wrapper .icon[data-v-49938287] {\n  background: #16e9cd;\n  -webkit-animation-name: icon-data-v-49938287;\n          animation-name: icon-data-v-49938287;\n  -webkit-animation-duration: 0.4s;\n          animation-duration: 0.4s;\n  -webkit-animation-timing-function: cubic-bezier(0.075, 0.82, 0.165, 1);\n          animation-timing-function: cubic-bezier(0.075, 0.82, 0.165, 1);\n}\n.main-checkbox-wrapper label[data-v-49938287] {\n  margin: 0;\n  font-weight: 450;\n  font-size: 16px;\n  cursor: pointer;\n  color: gray;\n}\n.main-checkbox-wrapper input[type=checkbox][data-v-49938287] {\n  display: none;\n}\n.main-checkbox-wrapper.small .no-icon[data-v-49938287] {\n  font-size: 9px;\n  padding: 7px;\n}\n.main-checkbox-wrapper.small label[data-v-49938287] {\n  font-size: 12px;\n}\n@media screen and (max-width: 800px) {\n.main-checkbox-wrapper .no-icon[data-v-49938287] {\n    width: 15px;\n    height: 15px;\n    font-size: 10px;\n    padding: 8px;\n}\n.main-checkbox-wrapper label[data-v-49938287] {\n    font-size: 14px;\n}\n}\n@-webkit-keyframes icon-data-v-49938287 {\nfrom {\n    transform: scale(0);\n    transform: rotateZ(75deg);\n}\nto {\n    transform: scale(1);\n    transform: rotateZ(0deg);\n}\n}\n@keyframes icon-data-v-49938287 {\nfrom {\n    transform: scale(0);\n    transform: rotateZ(75deg);\n}\nto {\n    transform: scale(1);\n    transform: rotateZ(0deg);\n}\n}", ""]);
// Exports
module.exports = exports;


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, exports, __webpack_require__) => {

// Imports
var ___CSS_LOADER_API_IMPORT___ = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
exports = ___CSS_LOADER_API_IMPORT___(false);
// Module
exports.push([module.id, ".small-msg[data-v-4b58166c] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.assessment-section-answering-badge .main-section[data-v-4b58166c] {\n  width: 100%;\n  background: aquamarine;\n  color: gray;\n  font-size: 14px;\n  padding: 5px;\n  margin: 0 0 10px;\n  text-transform: capitalize;\n}\n.assessment-section-answering-badge .instruction[data-v-4b58166c] {\n  background: white;\n  padding: 5px;\n  text-align: center;\n}", ""]);
// Exports
module.exports = exports;


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, exports, __webpack_require__) => {

// Imports
var ___CSS_LOADER_API_IMPORT___ = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
exports = ___CSS_LOADER_API_IMPORT___(false);
// Module
exports.push([module.id, ".small-msg[data-v-bbeb6d04] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.possible-answer-badge-wrapper[data-v-bbeb6d04] {\n  cursor: pointer;\n  padding: 0 5px;\n  border-radius: 10px;\n  margin: 5px auto;\n}\n.possible-answer-badge-wrapper .true-false[data-v-bbeb6d04] {\n  display: flex;\n  width: -webkit-fit-content;\n  width: -moz-fit-content;\n  width: fit-content;\n  align-items: center;\n  padding: 10px 10px 10px 0;\n  width: 100%;\n}\n.possible-answer-badge-wrapper .true-false .list-identifier[data-v-bbeb6d04] {\n  width: 10px;\n  height: 10px;\n  border-radius: 100%;\n  background: gray;\n  margin-right: 10px;\n  position: relative;\n}\n.possible-answer-badge-wrapper .true-false .checkbox[data-v-bbeb6d04] {\n  margin-left: auto;\n  margin-right: -10px;\n}\n.possible-answer-badge-wrapper .option[data-v-bbeb6d04] {\n  display: flex;\n  width: -webkit-fit-content;\n  width: -moz-fit-content;\n  width: fit-content;\n  align-items: center;\n  padding: 10px 10px 10px 0;\n  width: 100%;\n}\n.possible-answer-badge-wrapper .option .list-identifier[data-v-bbeb6d04] {\n  color: gray;\n  margin-right: 10px;\n  position: relative;\n}\n.possible-answer-badge-wrapper .option .item[data-v-bbeb6d04] {\n  min-width: -webkit-fit-content;\n  min-width: -moz-fit-content;\n  min-width: fit-content;\n}\n.possible-answer-badge-wrapper .option .checkbox[data-v-bbeb6d04] {\n  margin-left: auto;\n  margin-right: -10px;\n}\n.possible-answer-badge-wrapper .arrange[data-v-bbeb6d04] {\n  display: flex;\n  align-items: center;\n  padding: 10px;\n}\n.possible-answer-badge-wrapper .arrange .list-identifier[data-v-bbeb6d04] {\n  width: 10px;\n  margin-right: 5px;\n  height: 2px;\n  background: black;\n  position: relative;\n}\n.possible-answer-badge-wrapper .arrange .list-identifier[data-v-bbeb6d04]::before, .possible-answer-badge-wrapper .arrange .list-identifier[data-v-bbeb6d04]::after {\n  content: \"\";\n  width: 12px;\n  height: 2px;\n  background: gray;\n  position: absolute;\n  left: 0;\n}\n.possible-answer-badge-wrapper .arrange .list-identifier[data-v-bbeb6d04]::before {\n  transform: rotateZ(15deg);\n  top: -5px;\n}\n.possible-answer-badge-wrapper .arrange .list-identifier[data-v-bbeb6d04]::after {\n  top: 5px;\n  transform: rotateZ(-15deg);\n}\n.possible-answer-badge-wrapper .flow[data-v-bbeb6d04] {\n  display: block;\n  text-align: center;\n}\n.possible-answer-badge-wrapper .flow .list-identifier[data-v-bbeb6d04] {\n  width: 2px;\n  margin-right: 5px;\n  height: 30px;\n  background: black;\n  position: relative;\n  margin: 0 auto;\n}\n.possible-answer-badge-wrapper .flow .list-identifier[data-v-bbeb6d04]::before, .possible-answer-badge-wrapper .flow .list-identifier[data-v-bbeb6d04]::after {\n  content: \"\";\n  width: 12px;\n  height: 2px;\n  background: gray;\n  position: absolute;\n  bottom: 1px;\n  background: brown;\n}\n.possible-answer-badge-wrapper .flow .list-identifier[data-v-bbeb6d04]::before {\n  transform: rotateZ(55deg);\n  left: -8px;\n}\n.possible-answer-badge-wrapper .flow .list-identifier[data-v-bbeb6d04]::after {\n  transform: rotateZ(125deg);\n  left: -2px;\n}\n.possible-answer-badge-wrapper[data-v-bbeb6d04]:hover {\n  background: aliceblue;\n  transition-timing-function: cubic-bezier(0.86, 0, 0.07, 1);\n  transition-property: color, background-color;\n  transition-duration: 0.4s;\n}\n.possible-answer-badge-wrapper.no-drag[data-v-bbeb6d04]:hover {\n  background: inherit;\n}\n.possible-answer-badge-wrapper.removed[data-v-bbeb6d04] {\n  box-shadow: 0 0 1px gray;\n}\n.possible-answer-badge-wrapper.removed .option[data-v-bbeb6d04], .possible-answer-badge-wrapper.removed .true-false[data-v-bbeb6d04], .possible-answer-badge-wrapper.removed .flow[data-v-bbeb6d04], .possible-answer-badge-wrapper.removed .arrange[data-v-bbeb6d04] {\n  display: flex;\n  justify-content: center;\n}\n.possible-answer-badge-wrapper.correct[data-v-bbeb6d04] {\n  background: darkgreen;\n  color: white;\n  transition-timing-function: cubic-bezier(0.86, 0, 0.07, 1);\n  transition-property: color, background-color;\n  transition-duration: 0.8s;\n}\n.possible-answer-badge-wrapper.correct .option .list-identifier[data-v-bbeb6d04] {\n  color: white;\n}", ""]);
// Exports
module.exports = exports;


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, exports, __webpack_require__) => {

// Imports
var ___CSS_LOADER_API_IMPORT___ = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
exports = ___CSS_LOADER_API_IMPORT___(false);
// Module
exports.push([module.id, ".small-msg[data-v-10862390] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.assessment-section-answering-form .main-section[data-v-10862390] {\n  width: 100%;\n  background: aquamarine;\n  color: gray;\n  font-size: 14px;\n  padding: 5px;\n  margin: 0 0 10px;\n}", ""]);
// Exports
module.exports = exports;


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true&":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, exports, __webpack_require__) => {

// Imports
var ___CSS_LOADER_API_IMPORT___ = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
exports = ___CSS_LOADER_API_IMPORT___(false);
// Module
exports.push([module.id, ".small-msg[data-v-6fc3c8bc] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.work-answering-form-wrapper .welcome-form[data-v-6fc3c8bc] {\n  position: relative;\n}\n.work-answering-form-wrapper .welcome-form .loading[data-v-6fc3c8bc] {\n  position: sticky;\n  width: 100%;\n  text-align: center;\n  top: 10px;\n  z-index: 1;\n  top: 49%;\n}\n.work-answering-form-wrapper .welcome-form .section[data-v-6fc3c8bc] {\n  font-size: 12px;\n  font-weight: 500;\n  border-bottom: 1px solid gray;\n  margin: 30px 0 10px;\n  text-transform: capitalize;\n}", ""]);
// Exports
module.exports = exports;


/***/ }),

/***/ "./resources/js/components/MainCheckbox.vue":
/*!**************************************************!*\
  !*** ./resources/js/components/MainCheckbox.vue ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _MainCheckbox_vue_vue_type_template_id_49938287_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MainCheckbox.vue?vue&type=template&id=49938287&scoped=true& */ "./resources/js/components/MainCheckbox.vue?vue&type=template&id=49938287&scoped=true&");
/* harmony import */ var _MainCheckbox_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MainCheckbox.vue?vue&type=script&lang=js& */ "./resources/js/components/MainCheckbox.vue?vue&type=script&lang=js&");
/* harmony import */ var _MainCheckbox_vue_vue_type_style_index_0_id_49938287_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true& */ "./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__.default)(
  _MainCheckbox_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _MainCheckbox_vue_vue_type_template_id_49938287_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _MainCheckbox_vue_vue_type_template_id_49938287_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "49938287",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/MainCheckbox.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue":
/*!*******************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _AssessmentSectionAnsweringBadge_vue_vue_type_template_id_4b58166c_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AssessmentSectionAnsweringBadge.vue?vue&type=template&id=4b58166c&scoped=true& */ "./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=template&id=4b58166c&scoped=true&");
/* harmony import */ var _AssessmentSectionAnsweringBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AssessmentSectionAnsweringBadge.vue?vue&type=script&lang=js& */ "./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=script&lang=js&");
/* harmony import */ var _AssessmentSectionAnsweringBadge_vue_vue_type_style_index_0_id_4b58166c_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true& */ "./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__.default)(
  _AssessmentSectionAnsweringBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _AssessmentSectionAnsweringBadge_vue_vue_type_template_id_4b58166c_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _AssessmentSectionAnsweringBadge_vue_vue_type_template_id_4b58166c_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "4b58166c",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/dashboard/PossibleAnswerBadge.vue":
/*!*******************************************************************!*\
  !*** ./resources/js/components/dashboard/PossibleAnswerBadge.vue ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _PossibleAnswerBadge_vue_vue_type_template_id_bbeb6d04_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./PossibleAnswerBadge.vue?vue&type=template&id=bbeb6d04&scoped=true& */ "./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=template&id=bbeb6d04&scoped=true&");
/* harmony import */ var _PossibleAnswerBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./PossibleAnswerBadge.vue?vue&type=script&lang=js& */ "./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=script&lang=js&");
/* harmony import */ var _PossibleAnswerBadge_vue_vue_type_style_index_0_id_bbeb6d04_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true& */ "./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__.default)(
  _PossibleAnswerBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _PossibleAnswerBadge_vue_vue_type_template_id_bbeb6d04_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _PossibleAnswerBadge_vue_vue_type_template_id_bbeb6d04_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "bbeb6d04",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/dashboard/PossibleAnswerBadge.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/dashboard/QuestionAnsweringBadge.vue":
/*!**********************************************************************!*\
  !*** ./resources/js/components/dashboard/QuestionAnsweringBadge.vue ***!
  \**********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _QuestionAnsweringBadge_vue_vue_type_template_id_6e17532a_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./QuestionAnsweringBadge.vue?vue&type=template&id=6e17532a&scoped=true& */ "./resources/js/components/dashboard/QuestionAnsweringBadge.vue?vue&type=template&id=6e17532a&scoped=true&");
/* harmony import */ var _QuestionAnsweringBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./QuestionAnsweringBadge.vue?vue&type=script&lang=js& */ "./resources/js/components/dashboard/QuestionAnsweringBadge.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _QuestionAnsweringBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _QuestionAnsweringBadge_vue_vue_type_template_id_6e17532a_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _QuestionAnsweringBadge_vue_vue_type_template_id_6e17532a_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "6e17532a",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/dashboard/QuestionAnsweringBadge.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/forms/AnswerForm.vue":
/*!******************************************************!*\
  !*** ./resources/js/components/forms/AnswerForm.vue ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _AnswerForm_vue_vue_type_template_id_6a076ced_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AnswerForm.vue?vue&type=template&id=6a076ced&scoped=true& */ "./resources/js/components/forms/AnswerForm.vue?vue&type=template&id=6a076ced&scoped=true&");
/* harmony import */ var _AnswerForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AnswerForm.vue?vue&type=script&lang=js& */ "./resources/js/components/forms/AnswerForm.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _AnswerForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _AnswerForm_vue_vue_type_template_id_6a076ced_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _AnswerForm_vue_vue_type_template_id_6a076ced_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "6a076ced",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/forms/AnswerForm.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/forms/AssessmentSectionAnsweringForm.vue":
/*!**************************************************************************!*\
  !*** ./resources/js/components/forms/AssessmentSectionAnsweringForm.vue ***!
  \**************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _AssessmentSectionAnsweringForm_vue_vue_type_template_id_10862390_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AssessmentSectionAnsweringForm.vue?vue&type=template&id=10862390&scoped=true& */ "./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=template&id=10862390&scoped=true&");
/* harmony import */ var _AssessmentSectionAnsweringForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AssessmentSectionAnsweringForm.vue?vue&type=script&lang=js& */ "./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=script&lang=js&");
/* harmony import */ var _AssessmentSectionAnsweringForm_vue_vue_type_style_index_0_id_10862390_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true& */ "./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__.default)(
  _AssessmentSectionAnsweringForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _AssessmentSectionAnsweringForm_vue_vue_type_template_id_10862390_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _AssessmentSectionAnsweringForm_vue_vue_type_template_id_10862390_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "10862390",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/forms/AssessmentSectionAnsweringForm.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/forms/WorkAnsweringForm.vue":
/*!*************************************************************!*\
  !*** ./resources/js/components/forms/WorkAnsweringForm.vue ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _WorkAnsweringForm_vue_vue_type_template_id_6fc3c8bc_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./WorkAnsweringForm.vue?vue&type=template&id=6fc3c8bc&scoped=true& */ "./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=template&id=6fc3c8bc&scoped=true&");
/* harmony import */ var _WorkAnsweringForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./WorkAnsweringForm.vue?vue&type=script&lang=js& */ "./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=script&lang=js&");
/* harmony import */ var _WorkAnsweringForm_vue_vue_type_style_index_0_id_6fc3c8bc_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true& */ "./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__.default)(
  _WorkAnsweringForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _WorkAnsweringForm_vue_vue_type_template_id_6fc3c8bc_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _WorkAnsweringForm_vue_vue_type_template_id_6fc3c8bc_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "6fc3c8bc",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/forms/WorkAnsweringForm.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/specials/DraggableComponent.vue":
/*!*****************************************************************!*\
  !*** ./resources/js/components/specials/DraggableComponent.vue ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _DraggableComponent_vue_vue_type_template_id_1b4a61ea_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./DraggableComponent.vue?vue&type=template&id=1b4a61ea&scoped=true& */ "./resources/js/components/specials/DraggableComponent.vue?vue&type=template&id=1b4a61ea&scoped=true&");
/* harmony import */ var _DraggableComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./DraggableComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/specials/DraggableComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _DraggableComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _DraggableComponent_vue_vue_type_template_id_1b4a61ea_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _DraggableComponent_vue_vue_type_template_id_1b4a61ea_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "1b4a61ea",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/specials/DraggableComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/specials/DroppableComponent.vue":
/*!*****************************************************************!*\
  !*** ./resources/js/components/specials/DroppableComponent.vue ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _DroppableComponent_vue_vue_type_template_id_2b7b0408_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./DroppableComponent.vue?vue&type=template&id=2b7b0408&scoped=true& */ "./resources/js/components/specials/DroppableComponent.vue?vue&type=template&id=2b7b0408&scoped=true&");
/* harmony import */ var _DroppableComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./DroppableComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/specials/DroppableComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _DroppableComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _DroppableComponent_vue_vue_type_template_id_2b7b0408_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _DroppableComponent_vue_vue_type_template_id_2b7b0408_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "2b7b0408",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/specials/DroppableComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/MainCheckbox.vue?vue&type=script&lang=js&":
/*!***************************************************************************!*\
  !*** ./resources/js/components/MainCheckbox.vue?vue&type=script&lang=js& ***!
  \***************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MainCheckbox.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionAnsweringBadge.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PossibleAnswerBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PossibleAnswerBadge.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PossibleAnswerBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/dashboard/QuestionAnsweringBadge.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************!*\
  !*** ./resources/js/components/dashboard/QuestionAnsweringBadge.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionAnsweringBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./QuestionAnsweringBadge.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionAnsweringBadge.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionAnsweringBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/forms/AnswerForm.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/js/components/forms/AnswerForm.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AnswerForm.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AnswerForm.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionAnsweringForm.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkAnsweringForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./WorkAnsweringForm.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkAnsweringForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/specials/DraggableComponent.vue?vue&type=script&lang=js&":
/*!******************************************************************************************!*\
  !*** ./resources/js/components/specials/DraggableComponent.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DraggableComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./DraggableComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/DraggableComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DraggableComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/specials/DroppableComponent.vue?vue&type=script&lang=js&":
/*!******************************************************************************************!*\
  !*** ./resources/js/components/specials/DroppableComponent.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DroppableComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./DroppableComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/DroppableComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DroppableComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/MainCheckbox.vue?vue&type=template&id=49938287&scoped=true&":
/*!*********************************************************************************************!*\
  !*** ./resources/js/components/MainCheckbox.vue?vue&type=template&id=49938287&scoped=true& ***!
  \*********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_template_id_49938287_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_template_id_49938287_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_template_id_49938287_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MainCheckbox.vue?vue&type=template&id=49938287&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=template&id=49938287&scoped=true&");


/***/ }),

/***/ "./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=template&id=4b58166c&scoped=true&":
/*!**************************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=template&id=4b58166c&scoped=true& ***!
  \**************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_template_id_4b58166c_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_template_id_4b58166c_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_template_id_4b58166c_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionAnsweringBadge.vue?vue&type=template&id=4b58166c&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=template&id=4b58166c&scoped=true&");


/***/ }),

/***/ "./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=template&id=bbeb6d04&scoped=true&":
/*!**************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=template&id=bbeb6d04&scoped=true& ***!
  \**************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PossibleAnswerBadge_vue_vue_type_template_id_bbeb6d04_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PossibleAnswerBadge_vue_vue_type_template_id_bbeb6d04_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PossibleAnswerBadge_vue_vue_type_template_id_bbeb6d04_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PossibleAnswerBadge.vue?vue&type=template&id=bbeb6d04&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=template&id=bbeb6d04&scoped=true&");


/***/ }),

/***/ "./resources/js/components/dashboard/QuestionAnsweringBadge.vue?vue&type=template&id=6e17532a&scoped=true&":
/*!*****************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/QuestionAnsweringBadge.vue?vue&type=template&id=6e17532a&scoped=true& ***!
  \*****************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionAnsweringBadge_vue_vue_type_template_id_6e17532a_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionAnsweringBadge_vue_vue_type_template_id_6e17532a_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionAnsweringBadge_vue_vue_type_template_id_6e17532a_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./QuestionAnsweringBadge.vue?vue&type=template&id=6e17532a&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionAnsweringBadge.vue?vue&type=template&id=6e17532a&scoped=true&");


/***/ }),

/***/ "./resources/js/components/forms/AnswerForm.vue?vue&type=template&id=6a076ced&scoped=true&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/forms/AnswerForm.vue?vue&type=template&id=6a076ced&scoped=true& ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerForm_vue_vue_type_template_id_6a076ced_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerForm_vue_vue_type_template_id_6a076ced_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerForm_vue_vue_type_template_id_6a076ced_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AnswerForm.vue?vue&type=template&id=6a076ced&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AnswerForm.vue?vue&type=template&id=6a076ced&scoped=true&");


/***/ }),

/***/ "./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=template&id=10862390&scoped=true&":
/*!*********************************************************************************************************************!*\
  !*** ./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=template&id=10862390&scoped=true& ***!
  \*********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringForm_vue_vue_type_template_id_10862390_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringForm_vue_vue_type_template_id_10862390_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringForm_vue_vue_type_template_id_10862390_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionAnsweringForm.vue?vue&type=template&id=10862390&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=template&id=10862390&scoped=true&");


/***/ }),

/***/ "./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=template&id=6fc3c8bc&scoped=true&":
/*!********************************************************************************************************!*\
  !*** ./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=template&id=6fc3c8bc&scoped=true& ***!
  \********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkAnsweringForm_vue_vue_type_template_id_6fc3c8bc_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkAnsweringForm_vue_vue_type_template_id_6fc3c8bc_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkAnsweringForm_vue_vue_type_template_id_6fc3c8bc_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./WorkAnsweringForm.vue?vue&type=template&id=6fc3c8bc&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=template&id=6fc3c8bc&scoped=true&");


/***/ }),

/***/ "./resources/js/components/specials/DraggableComponent.vue?vue&type=template&id=1b4a61ea&scoped=true&":
/*!************************************************************************************************************!*\
  !*** ./resources/js/components/specials/DraggableComponent.vue?vue&type=template&id=1b4a61ea&scoped=true& ***!
  \************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DraggableComponent_vue_vue_type_template_id_1b4a61ea_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DraggableComponent_vue_vue_type_template_id_1b4a61ea_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DraggableComponent_vue_vue_type_template_id_1b4a61ea_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./DraggableComponent.vue?vue&type=template&id=1b4a61ea&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/DraggableComponent.vue?vue&type=template&id=1b4a61ea&scoped=true&");


/***/ }),

/***/ "./resources/js/components/specials/DroppableComponent.vue?vue&type=template&id=2b7b0408&scoped=true&":
/*!************************************************************************************************************!*\
  !*** ./resources/js/components/specials/DroppableComponent.vue?vue&type=template&id=2b7b0408&scoped=true& ***!
  \************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DroppableComponent_vue_vue_type_template_id_2b7b0408_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DroppableComponent_vue_vue_type_template_id_2b7b0408_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DroppableComponent_vue_vue_type_template_id_2b7b0408_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./DroppableComponent.vue?vue&type=template&id=2b7b0408&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/DroppableComponent.vue?vue&type=template&id=2b7b0408&scoped=true&");


/***/ }),

/***/ "./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true&":
/*!************************************************************************************************************!*\
  !*** ./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true& ***!
  \************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_style_index_0_id_49938287_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-style-loader/index.js!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true& */ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_style_index_0_id_49938287_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_style_index_0_id_49938287_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ var __WEBPACK_REEXPORT_OBJECT__ = {};
/* harmony reexport (unknown) */ for(const __WEBPACK_IMPORT_KEY__ in _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_style_index_0_id_49938287_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== "default") __WEBPACK_REEXPORT_OBJECT__[__WEBPACK_IMPORT_KEY__] = () => _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_style_index_0_id_49938287_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__[__WEBPACK_IMPORT_KEY__]
/* harmony reexport (unknown) */ __webpack_require__.d(__webpack_exports__, __WEBPACK_REEXPORT_OBJECT__);


/***/ }),

/***/ "./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true&":
/*!*****************************************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true& ***!
  \*****************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_style_index_0_id_4b58166c_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-style-loader/index.js!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true& */ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_style_index_0_id_4b58166c_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_style_index_0_id_4b58166c_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ var __WEBPACK_REEXPORT_OBJECT__ = {};
/* harmony reexport (unknown) */ for(const __WEBPACK_IMPORT_KEY__ in _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_style_index_0_id_4b58166c_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== "default") __WEBPACK_REEXPORT_OBJECT__[__WEBPACK_IMPORT_KEY__] = () => _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_style_index_0_id_4b58166c_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__[__WEBPACK_IMPORT_KEY__]
/* harmony reexport (unknown) */ __webpack_require__.d(__webpack_exports__, __WEBPACK_REEXPORT_OBJECT__);


/***/ }),

/***/ "./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true&":
/*!*****************************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true& ***!
  \*****************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_PossibleAnswerBadge_vue_vue_type_style_index_0_id_bbeb6d04_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-style-loader/index.js!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true& */ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_PossibleAnswerBadge_vue_vue_type_style_index_0_id_bbeb6d04_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_PossibleAnswerBadge_vue_vue_type_style_index_0_id_bbeb6d04_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ var __WEBPACK_REEXPORT_OBJECT__ = {};
/* harmony reexport (unknown) */ for(const __WEBPACK_IMPORT_KEY__ in _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_PossibleAnswerBadge_vue_vue_type_style_index_0_id_bbeb6d04_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== "default") __WEBPACK_REEXPORT_OBJECT__[__WEBPACK_IMPORT_KEY__] = () => _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_PossibleAnswerBadge_vue_vue_type_style_index_0_id_bbeb6d04_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__[__WEBPACK_IMPORT_KEY__]
/* harmony reexport (unknown) */ __webpack_require__.d(__webpack_exports__, __WEBPACK_REEXPORT_OBJECT__);


/***/ }),

/***/ "./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true&":
/*!************************************************************************************************************************************!*\
  !*** ./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true& ***!
  \************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringForm_vue_vue_type_style_index_0_id_10862390_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-style-loader/index.js!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true& */ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringForm_vue_vue_type_style_index_0_id_10862390_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringForm_vue_vue_type_style_index_0_id_10862390_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ var __WEBPACK_REEXPORT_OBJECT__ = {};
/* harmony reexport (unknown) */ for(const __WEBPACK_IMPORT_KEY__ in _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringForm_vue_vue_type_style_index_0_id_10862390_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== "default") __WEBPACK_REEXPORT_OBJECT__[__WEBPACK_IMPORT_KEY__] = () => _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringForm_vue_vue_type_style_index_0_id_10862390_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__[__WEBPACK_IMPORT_KEY__]
/* harmony reexport (unknown) */ __webpack_require__.d(__webpack_exports__, __WEBPACK_REEXPORT_OBJECT__);


/***/ }),

/***/ "./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true&":
/*!***********************************************************************************************************************!*\
  !*** ./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true& ***!
  \***********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkAnsweringForm_vue_vue_type_style_index_0_id_6fc3c8bc_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-style-loader/index.js!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true& */ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkAnsweringForm_vue_vue_type_style_index_0_id_6fc3c8bc_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkAnsweringForm_vue_vue_type_style_index_0_id_6fc3c8bc_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ var __WEBPACK_REEXPORT_OBJECT__ = {};
/* harmony reexport (unknown) */ for(const __WEBPACK_IMPORT_KEY__ in _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkAnsweringForm_vue_vue_type_style_index_0_id_6fc3c8bc_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== "default") __WEBPACK_REEXPORT_OBJECT__[__WEBPACK_IMPORT_KEY__] = () => _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkAnsweringForm_vue_vue_type_style_index_0_id_6fc3c8bc_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__[__WEBPACK_IMPORT_KEY__]
/* harmony reexport (unknown) */ __webpack_require__.d(__webpack_exports__, __WEBPACK_REEXPORT_OBJECT__);


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=template&id=49938287&scoped=true&":
/*!************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=template&id=49938287&scoped=true& ***!
  \************************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "main-checkbox-wrapper", class: { small: _vm.small } },
    [
      _c("input", {
        directives: [
          {
            name: "model",
            rawName: "v-model",
            value: _vm.inputValue,
            expression: "inputValue"
          }
        ],
        attrs: { type: "checkbox", id: _vm.randomId },
        domProps: {
          checked: Array.isArray(_vm.inputValue)
            ? _vm._i(_vm.inputValue, null) > -1
            : _vm.inputValue
        },
        on: {
          change: function($event) {
            var $$a = _vm.inputValue,
              $$el = $event.target,
              $$c = $$el.checked ? true : false
            if (Array.isArray($$a)) {
              var $$v = null,
                $$i = _vm._i($$a, $$v)
              if ($$el.checked) {
                $$i < 0 && (_vm.inputValue = $$a.concat([$$v]))
              } else {
                $$i > -1 &&
                  (_vm.inputValue = $$a
                    .slice(0, $$i)
                    .concat($$a.slice($$i + 1)))
              }
            } else {
              _vm.inputValue = $$c
            }
          }
        }
      }),
      _vm._v(" "),
      _c(
        "div",
        {
          ref: "checkboxicon",
          staticClass: "no-icon",
          on: { click: _vm.toggleValue }
        },
        [
          _vm.value
            ? _c("font-awesome-icon", { attrs: { icon: ["fa", "check"] } })
            : _vm._e()
        ],
        1
      ),
      _vm._v(" "),
      _c("label", { attrs: { for: _vm.randomId } }, [_vm._v(_vm._s(_vm.label))])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=template&id=4b58166c&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=template&id=4b58166c&scoped=true& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "assessment-section-answering-badge" }, [
    _c(
      "div",
      {
        staticClass:
          "shadow-sm border-b-2 w-full flex-shrink-0 bg-gray-50 h-10 flex justify-center items-center mt-1 mb-3 relative"
      },
      [
        _c(
          "div",
          { staticClass: "absolute left-0 top-0 text-gray-400 text-xs" },
          [_vm._v("current section")]
        ),
        _vm._v(" "),
        _c("div", { staticClass: "text-lg font-header capitalize" }, [
          _vm._v(_vm._s(_vm.assessmentSection.name))
        ])
      ]
    ),
    _vm._v(" "),
    _c("div", { staticClass: "w-full px-5 mb-2 flex-shrink-0" }, [
      _vm.assessmentSection.instruction
        ? _c(
            "div",
            {
              staticClass: "overflow-ellipsis text-center text-gray-500 text-sm"
            },
            [_vm._v(_vm._s(_vm.assessmentSection.instruction))]
          )
        : _vm._e(),
      _vm._v(" "),
      _c("div", { staticClass: "text-xs text-right text-gray-400" }, [
        _vm._v(_vm._s(_vm.assessmentSection.questions.length + " questions"))
      ]),
      _vm._v(" "),
      _vm.assessmentSection.random
        ? _c("div", { staticClass: "text-gray-400 text-right text-xs" }, [
            _vm._v("the questions where selected randomly")
          ])
        : _vm._e()
    ]),
    _vm._v(" "),
    _c(
      "div",
      { staticClass: "h-full max-h-3/4 flex-shrink mb-2 overflow-y-auto p-2" },
      _vm._l(_vm.assessmentSection.questions, function(question, index) {
        return _c("question-answering-badge", {
          key: index,
          attrs: { question: question }
        })
      }),
      1
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=template&id=bbeb6d04&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=template&id=bbeb6d04&scoped=true& ***!
  \*****************************************************************************************************************************************************************************************************************************************************/
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
    "draggable-component",
    {
      attrs: {
        dataTransfer: {
          fromPossibleAnswerIndex: _vm.possibleAnswerIndex,
          removed: _vm.removed
        },
        drag: _vm.drag
      }
    },
    [
      _c(
        "droppable-component",
        {
          attrs: {
            dataTransfer: {
              toPossibleAnswerIndex: _vm.possibleAnswerIndex
            }
          },
          on: { drop: _vm.movePossibleAnswer }
        },
        [
          _vm.possibleAnswer
            ? _c(
                "div",
                {
                  staticClass: "possible-answer-badge-wrapper",
                  class: {
                    correct: _vm.possibleAnswer.correct,
                    removed: _vm.removed,
                    "no-drag": !_vm.drag
                  },
                  on: { dblclick: _vm.editPossibleAnswer }
                },
                [
                  _vm.computedOption
                    ? _c(
                        "div",
                        { staticClass: "option" },
                        [
                          !_vm.removed
                            ? _c("div", { staticClass: "list-identifier" }, [
                                _vm._v(
                                  "\n                    " +
                                    _vm._s(_vm.computedPosition) +
                                    "\n                "
                                )
                              ])
                            : _vm._e(),
                          _vm._v(" "),
                          _c("div", { staticClass: "item" }, [
                            _vm._v(
                              "\n                    " +
                                _vm._s(_vm.possibleAnswer.option) +
                                "\n                "
                            )
                          ]),
                          _vm._v(" "),
                          _vm.autoMark && !_vm.removed
                            ? _c("main-checkbox", {
                                staticClass: "checkbox",
                                attrs: {
                                  small: true,
                                  label: "",
                                  title: "check if this is the correct answer"
                                },
                                model: {
                                  value: _vm.correct,
                                  callback: function($$v) {
                                    _vm.correct = $$v
                                  },
                                  expression: "correct"
                                }
                              })
                            : _vm._e()
                        ],
                        1
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.computedTrueOrFalse
                    ? _c(
                        "div",
                        { staticClass: "true-false" },
                        [
                          _c("div", { staticClass: "list-identifier" }),
                          _vm._v(" "),
                          _c("div", { staticClass: "item" }, [
                            _vm._v(
                              "\n                    " +
                                _vm._s(_vm.possibleAnswer.option) +
                                "\n                "
                            )
                          ]),
                          _vm._v(" "),
                          _vm.autoMark && !_vm.removed
                            ? _c("main-checkbox", {
                                staticClass: "checkbox",
                                attrs: {
                                  small: true,
                                  label: "",
                                  title: "check if this is the correct answer"
                                },
                                model: {
                                  value: _vm.correct,
                                  callback: function($$v) {
                                    _vm.correct = $$v
                                  },
                                  expression: "correct"
                                }
                              })
                            : _vm._e()
                        ],
                        1
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.computedArrange
                    ? _c("div", { staticClass: "arrange" }, [
                        !_vm.removed
                          ? _c("div", { staticClass: "list-identifier" })
                          : _vm._e(),
                        _vm._v(" "),
                        _c("div", { staticClass: "item" }, [
                          _vm._v(
                            "\n                    " +
                              _vm._s(_vm.possibleAnswer.option) +
                              "\n                "
                          )
                        ])
                      ])
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.computedFlow
                    ? _c("div", { staticClass: "flow" }, [
                        _c("div", { staticClass: "item" }, [
                          _vm._v(
                            "\n                    " +
                              _vm._s(_vm.possibleAnswer.option) +
                              "\n                "
                          )
                        ]),
                        _vm._v(" "),
                        _vm.possibleAnswer.position !==
                          _vm.possibleAnswersLength && !_vm.removed
                          ? _c("div", { staticClass: "list-identifier" })
                          : _vm._e()
                      ])
                    : _vm._e()
                ]
              )
            : _vm._e()
        ]
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionAnsweringBadge.vue?vue&type=template&id=6e17532a&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionAnsweringBadge.vue?vue&type=template&id=6e17532a&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************************************************/
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
      !_vm.question
        ? _c("div", [_vm._v("sorry 😞, there is no question")])
        : _vm._e(),
      _vm._v(" "),
      _vm.question
        ? [
            _c("div", {}, [_vm._v(_vm._s(_vm.question.body))]),
            _vm._v(" "),
            _c("answer-form", {
              attrs: {
                answerType: _vm.question.answerType,
                possibleAnswers: _vm.question.possibleAnswers
              }
            })
          ]
        : _vm._e()
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AnswerForm.vue?vue&type=template&id=6a076ced&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AnswerForm.vue?vue&type=template&id=6a076ced&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************/
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
      _vm.computedAnswerType === "short_answer"
        ? _c("text-input", {
            attrs: {
              placeholder: "give a short answer to this question",
              bottomBorder: true
            },
            model: {
              value: _vm.answer.answer,
              callback: function($$v) {
                _vm.$set(_vm.answer, "answer", $$v)
              },
              expression: "answer.answer"
            }
          })
        : _vm._e(),
      _vm._v(" "),
      _vm.computedAnswerType === "long_answer"
        ? _c("text-textarea", {
            attrs: {
              placeholder: "give a long answer to this question",
              bottomBorder: true
            },
            model: {
              value: _vm.answer.answer,
              callback: function($$v) {
                _vm.$set(_vm.answer, "answer", $$v)
              },
              expression: "answer.answer"
            }
          })
        : _vm._e(),
      _vm._v(" "),
      _vm.possibleAnswers.length
        ? _c("droppable-component", { on: { drop: _vm.movePossibleAnswer } }, [
            _c(
              "div",
              { attrs: { id: "answers" } },
              _vm._l(_vm.possibleAnswers, function(
                possibleAnswer,
                possibleAnswerIndex
              ) {
                return _c("possible-answer-badge", {
                  key: possibleAnswerIndex,
                  attrs: {
                    possibleAnswerIndex: possibleAnswerIndex,
                    answerType: _vm.answerType,
                    drag: _vm.computedDrag,
                    possibleAnswer: possibleAnswer,
                    possibleAnswersLength: _vm.possibleAnswers.length
                  },
                  on: {
                    movePossibleAnswer: _vm.movePossibleAnswer,
                    possibleAnswerIsCorrect: _vm.possibleAnswerIsCorrect,
                    possibleAnswerIsWrong: _vm.possibleAnswerIsWrong
                  }
                })
              }),
              1
            )
          ])
        : _vm._e()
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=template&id=10862390&scoped=true&":
/*!************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=template&id=10862390&scoped=true& ***!
  \************************************************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "assessment-section-answering-form" },
    [
      _c("assessment-section-answering-badge", {
        staticClass: "h-full",
        attrs: { assessmentSection: _vm.currentAssessmentSection }
      }),
      _vm._v(" "),
      !_vm.currentAssessmentSection
        ? _c("div", [
            _vm._v(
              "\n        sorry😞, there is no section for this assessment\n    "
            )
          ])
        : _vm._e(),
      _vm._v(" "),
      _c("div", { staticClass: "flex-shrink-0 flex justify-around" }, [
        _c(
          "button",
          {
            staticClass:
              "text-gray-500 disabled:bg-gray-800 disabled:text-gray-200 p-2 border-b cursor-pointer hover:shadow-sm hover:bg-gray-50 rounded",
            attrs: {
              disabled:
                _vm.firstAssessmentSectionId === _vm.currentAssessmentSection.id
            },
            on: {
              click: function($event) {
                return _vm.clickedSectionNavigator("previous")
              }
            }
          },
          [_vm._v("previous")]
        ),
        _vm._v(" "),
        _c(
          "button",
          {
            staticClass:
              "text-gray-500 disabled:bg-gray-800 disabled:text-gray-200 p-2 border-b cursor-pointer hover:shadow-sm hover:bg-gray-50 rounded",
            attrs: {
              disabled:
                _vm.lastAssessmentSectionId === _vm.currentAssessmentSection.id
            },
            on: {
              click: function($event) {
                return _vm.clickedSectionNavigator("next")
              }
            }
          },
          [_vm._v("next")]
        )
      ])
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=template&id=6fc3c8bc&scoped=true&":
/*!***********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=template&id=6fc3c8bc&scoped=true& ***!
  \***********************************************************************************************************************************************************************************************************************************************/
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
    "just-fade",
    [
      _vm.show
        ? _c(
            "template",
            { slot: "transition" },
            [
              _c(
                "main-modal",
                {
                  staticClass: "work-answering-form-wrapper",
                  attrs: {
                    show: _vm.show,
                    mainOther: false,
                    requests: false,
                    long: true
                  },
                  on: { mainModalDisappear: _vm.closeModal }
                },
                [
                  _c(
                    "template",
                    { slot: "main" },
                    [
                      _c(
                        "welcome-form",
                        {
                          staticClass: "welcome-form",
                          attrs: { title: "assessment name", titleBadge: true }
                        },
                        [
                          _c(
                            "template",
                            { slot: "input" },
                            [
                              _c("auto-alert", {
                                attrs: {
                                  message: _vm.alertMessage,
                                  success: _vm.alertSuccess,
                                  danger: _vm.alertDanger,
                                  sticky: true
                                },
                                on: { hideAlert: _vm.clearAlert }
                              }),
                              _vm._v(" "),
                              _c("pulse-loader", {
                                staticClass: "loading",
                                attrs: { size: "20px", loading: _vm.loading }
                              }),
                              _vm._v(" "),
                              !_vm.computedHasNoData && !_vm.mainLoading
                                ? [
                                    _c("assessment-section-answering-form", {
                                      staticClass: "section-form",
                                      attrs: { assessment: _vm.assessment }
                                    })
                                  ]
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.computedHasNoData && !_vm.mainLoading
                                ? [
                                    _vm._v(
                                      "\n                            " +
                                        _vm._s(_vm.noDataMessage) +
                                        "\n                        "
                                    )
                                  ]
                                : _vm._e()
                            ],
                            2
                          ),
                          _vm._v(" "),
                          _c(
                            "template",
                            { slot: "buttons" },
                            [
                              _vm.computedAnswered
                                ? _c("post-button", {
                                    attrs: {
                                      buttonText: _vm.buttonText,
                                      buttonStyle: "success"
                                    },
                                    on: { click: _vm.clickedCreate }
                                  })
                                : _vm._e()
                            ],
                            1
                          )
                        ],
                        2
                      )
                    ],
                    1
                  )
                ],
                2
              )
            ],
            1
          )
        : _vm._e()
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/DraggableComponent.vue?vue&type=template&id=1b4a61ea&scoped=true&":
/*!***************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/DraggableComponent.vue?vue&type=template&id=1b4a61ea&scoped=true& ***!
  \***************************************************************************************************************************************************************************************************************************************************/
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
    {
      attrs: { draggable: _vm.drag },
      on: {
        dragover: function($event) {
          $event.preventDefault()
        },
        dragenter: function($event) {
          $event.preventDefault()
        },
        dragstart: function($event) {
          if ($event.target !== $event.currentTarget) {
            return null
          }
          $event.stopPropagation()
          return _vm.dragStart($event)
        }
      }
    },
    [_vm._t("default")],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/DroppableComponent.vue?vue&type=template&id=2b7b0408&scoped=true&":
/*!***************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/DroppableComponent.vue?vue&type=template&id=2b7b0408&scoped=true& ***!
  \***************************************************************************************************************************************************************************************************************************************************/
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
    {
      on: {
        dragover: function($event) {
          $event.preventDefault()
        },
        dragenter: function($event) {
          $event.preventDefault()
        },
        drop: function($event) {
          $event.stopPropagation()
          return _vm.drop($event)
        }
      }
    },
    [_vm._t("default")],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(/*! !!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true&");
if(content.__esModule) content = content.default;
if(typeof content === 'string') content = [[module.id, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var add = __webpack_require__(/*! !../../../node_modules/vue-style-loader/lib/addStylesClient.js */ "./node_modules/vue-style-loader/lib/addStylesClient.js").default
var update = add("67bb9fa8", content, false, {});
// Hot Module Replacement
if(false) {}

/***/ }),

/***/ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(/*! !!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true&");
if(content.__esModule) content = content.default;
if(typeof content === 'string') content = [[module.id, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var add = __webpack_require__(/*! !../../../../node_modules/vue-style-loader/lib/addStylesClient.js */ "./node_modules/vue-style-loader/lib/addStylesClient.js").default
var update = add("1c0f92a8", content, false, {});
// Hot Module Replacement
if(false) {}

/***/ }),

/***/ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(/*! !!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true&");
if(content.__esModule) content = content.default;
if(typeof content === 'string') content = [[module.id, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var add = __webpack_require__(/*! !../../../../node_modules/vue-style-loader/lib/addStylesClient.js */ "./node_modules/vue-style-loader/lib/addStylesClient.js").default
var update = add("99b52ab8", content, false, {});
// Hot Module Replacement
if(false) {}

/***/ }),

/***/ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(/*! !!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true&");
if(content.__esModule) content = content.default;
if(typeof content === 'string') content = [[module.id, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var add = __webpack_require__(/*! !../../../../node_modules/vue-style-loader/lib/addStylesClient.js */ "./node_modules/vue-style-loader/lib/addStylesClient.js").default
var update = add("176a282e", content, false, {});
// Hot Module Replacement
if(false) {}

/***/ }),

/***/ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(/*! !!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true&");
if(content.__esModule) content = content.default;
if(typeof content === 'string') content = [[module.id, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var add = __webpack_require__(/*! !../../../../node_modules/vue-style-loader/lib/addStylesClient.js */ "./node_modules/vue-style-loader/lib/addStylesClient.js").default
var update = add("29130d5e", content, false, {});
// Hot Module Replacement
if(false) {}

/***/ })

}]);