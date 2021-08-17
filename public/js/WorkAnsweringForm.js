"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["WorkAnsweringForm"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/EditDeleteButtons.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/EditDeleteButtons.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
  methods: {
    clickedButton: function clickedButton(text) {
      this.$emit('click', text);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    files: {
      type: Array,
      "default": function _default() {
        return null;
      }
    },
    localFiles: {
      type: Array,
      "default": function _default() {
        return null;
      }
    },
    hasRemove: {
      type: Boolean,
      "default": true
    },
    checkLocalFiles: {
      type: Boolean,
      "default": false
    },
    mainMessage: {
      type: String,
      "default": ''
    },
    removedMessage: {
      type: String,
      "default": ''
    }
  },
  data: function data() {
    return {
      removedFiles: [],
      mainFiles: []
    };
  },
  watch: {
    removedFiles: function removedFiles(newValue) {
      this.$emit('previewFiles', {
        removed: newValue,
        main: this.mainFiles
      });
    },
    files: {
      immediate: true,
      handler: function handler(newValue) {
        if (newValue) {
          this.mainFiles = newValue;
        }
      }
    }
  },
  methods: {
    clickedRemove: function clickedRemove(item, type) {
      var index = this.findFileIndex(item, type);

      if (index > -1) {
        if (type === 'main') {
          this.mainFiles.splice(index, 1);
          this.removedFiles.push(item);
        } else if (type === 'removed') {
          if (this.itemTypeInLocalFiles(item)) return;
          this.removedFiles.splice(index, 1);
          this.mainFiles.push(item);
        }
      }
    },
    findFileIndex: function findFileIndex(item, type) {
      var files = type === 'main' ? this.mainFiles : this.removedFiles;
      return files.findIndex(function (file) {
        return item.id === file.id && item.type === file.type;
      });
    },
    itemTypeInLocalFiles: function itemTypeInLocalFiles(item) {
      var _this = this;

      if (this.localFiles) {
        this.localFiles.forEach(function (file) {
          if (file && file.type.includes(item.type)) {
            _this.$emit('error', {
              lengthy: true,
              message: "please you cannot undo the removal of this file till you delete a ".concat(item.type, " from your locally uploaded files.")
            });

            return true;
          }
        });
      }

      return false;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationButton.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationButton.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
    disabled: {
      type: Boolean,
      "default": false
    },
    text: {
      type: String,
      "default": ''
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationComponent.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationComponent.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _NavigationButton_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NavigationButton.vue */ "./resources/js/components/NavigationButton.vue");
//
//
//
//
//
//
//
//
//
//
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
    NavigationButton: _NavigationButton_vue__WEBPACK_IMPORTED_MODULE_0__.default
  },
  props: {
    isFirst: {
      type: Boolean,
      "default": false
    },
    isLast: {
      type: Boolean,
      "default": false
    },
    buttons: {
      type: Array,
      "default": function _default() {
        return [];
      }
    }
  },
  methods: {
    clickedNavigator: function clickedNavigator(text) {
      this.$emit('clickedNavigator', text);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerBadge.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerBadge.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _FilesPreviewBackend_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../FilesPreviewBackend.vue */ "./resources/js/components/FilesPreviewBackend.vue");
/* harmony import */ var _mixins_Files_mixin__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../mixins/Files.mixin */ "./resources/js/mixins/Files.mixin.js");
/* harmony import */ var _PossibleAnswerBadge_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./PossibleAnswerBadge.vue */ "./resources/js/components/dashboard/PossibleAnswerBadge.vue");
/* harmony import */ var _specials_PartialMark_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../specials/PartialMark.vue */ "./resources/js/components/specials/PartialMark.vue");
/* harmony import */ var _EditDeleteButtons_vue__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../EditDeleteButtons.vue */ "./resources/js/components/EditDeleteButtons.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    FilesPreviewBackend: _FilesPreviewBackend_vue__WEBPACK_IMPORTED_MODULE_0__.default,
    PossibleAnswerBadge: _PossibleAnswerBadge_vue__WEBPACK_IMPORTED_MODULE_2__.default,
    PartialMark: _specials_PartialMark_vue__WEBPACK_IMPORTED_MODULE_3__.default,
    EditDeleteButtons: _EditDeleteButtons_vue__WEBPACK_IMPORTED_MODULE_4__.default
  },
  mixins: [_mixins_Files_mixin__WEBPACK_IMPORTED_MODULE_1__.default],
  props: {
    answer: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    mark: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    hasEditDeleteButtons: {
      type: Boolean,
      "default": function _default() {
        return false;
      }
    }
  },
  data: function data() {
    return {
      showMarkForm: false
    };
  },
  computed: {
    computedItemable: function computedItemable() {
      return this.answer;
    },
    computedIsCorrect: function computedIsCorrect() {
      if (!this.mark) {
        return false;
      }

      return this.mark.score == this.answer.question.scoreOver;
    },
    computedIsPartial: function computedIsPartial() {
      if (!this.mark) {
        return false;
      }

      return this.mark.score < this.answer.question.scoreOver && 0 < this.mark.score;
    },
    computedIsWrong: function computedIsWrong() {
      if (!this.mark) {
        return false;
      }

      return this.mark.score == 0;
    },
    computedPossibleAnswers: function computedPossibleAnswers() {
      var _this$answer$possible,
          _this = this;

      if (!((_this$answer$possible = this.answer.possibleAnswerIds) !== null && _this$answer$possible !== void 0 && _this$answer$possible.length)) {
        return [];
      }

      var possibleAnswers = [];
      this.answer.possibleAnswerIds.forEach(function (possibleAnswerId) {
        possibleAnswers.push(_this.answer.question.possibleAnswers.find(function (possibleAnswer) {
          return possibleAnswer.id == possibleAnswerId;
        }));
      });
      return possibleAnswers;
    },
    computedScore: function computedScore() {
      return this.answer.mark ? "".concat(this.answer.mark.score, "/").concat(this.answer.scoreOver) : '';
    },
    computedRemark: function computedRemark() {
      var _this$answer$mark;

      return (_this$answer$mark = this.answer.mark) !== null && _this$answer$mark !== void 0 && _this$answer$mark.remark ? "remarks: ".concat(this.answer.mark.remark) : '';
    },
    computedShowEditDeleteButtons: function computedShowEditDeleteButtons() {
      var _this$answer;

      return ((_this$answer = this.answer) === null || _this$answer === void 0 ? void 0 : _this$answer.mark) && this.answer.isMarker || this.hasEditDeleteButtons;
    }
  },
  methods: {
    clickedButton: function clickedButton(type) {
      if (type === 'edit') {
        type = 'update';
      }

      this.$emit('clickedButton', {
        type: type,
        answer: this.answer
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _QuestionAnsweringBadge_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./QuestionAnsweringBadge.vue */ "./resources/js/components/dashboard/QuestionAnsweringBadge.vue");
/* harmony import */ var _mixins_Timing_mixin__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../mixins/Timing.mixin */ "./resources/js/mixins/Timing.mixin.js");
/* harmony import */ var _mixins_PopUp_mixin__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../mixins/PopUp.mixin */ "./resources/js/mixins/PopUp.mixin.js");
/* harmony import */ var _AssessmentSectionInformationBadge_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./AssessmentSectionInformationBadge.vue */ "./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue");
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




/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    QuestionAnsweringBadge: _QuestionAnsweringBadge_vue__WEBPACK_IMPORTED_MODULE_0__.default,
    AssessmentSectionInformationBadge: _AssessmentSectionInformationBadge_vue__WEBPACK_IMPORTED_MODULE_3__.default
  },
  props: {
    assessmentSection: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    computedAccount: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    assessmentId: {
      "default": null
    },
    answers: {
      type: Array,
      "default": function _default() {
        return [];
      }
    },
    sectionAnswers: {
      type: Array,
      "default": function _default() {
        return [];
      }
    }
  },
  mixins: [_mixins_Timing_mixin__WEBPACK_IMPORTED_MODULE_1__.default, _mixins_PopUp_mixin__WEBPACK_IMPORTED_MODULE_2__.default],
  watch: {
    assessmentSection: {
      immediate: true,
      handler: function handler(newValue, oldValue) {
        this.initiate();
      }
    }
  },
  computed: {
    computedItem: function computedItem() {
      return {
        itemId: this.assessmentSection.id,
        item: 'assessmentSection'
      };
    },
    computedItemable: function computedItemable() {
      return this.assessmentSection ? _objectSpread(_objectSpread({}, this.assessmentSection), {}, {
        assessmentId: this.assessmentId
      }) : {};
    }
  },
  methods: {
    answered: function answered(data) {
      data.assessmentSectionId = this.assessmentSection.id;
      this.$emit('answered', data);
    },
    initiate: function initiate() {
      if (this.assessmentSection.initiated) {
        return;
      }

      if (!this.assessmentSection.duration) {
        this.timingItemWait = false;
        return;
      }

      this.timingItemWait = true;

      if (this.assessmentSection.timer) {
        this.startTimer();
        return;
      }

      this.showPopUp = true;
      this.$emit('initiated', this.assessmentSection);
    },
    clickedPopupResponse: function clickedPopupResponse(data) {
      if (data === 'continue') {
        this.startTimer();
      }

      if (data === 'cancel') {}

      this.showPopUp = false;
    },
    getQuestionAnswer: function getQuestionAnswer(question) {
      return this.sectionAnswers.find(function (answer) {
        return answer.question.id === question.id;
      });
    },
    getProvidedAnswer: function getProvidedAnswer(question) {
      var _this = this;

      return this.answers.find(function (answer) {
        return answer.questionId == question.id && answer.assessmentSectionId == _this.assessmentSection.id;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
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
//
//
//
//
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
    marking: {
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
    correctPossibleAnswers: {
      immediate: true,
      handler: function handler(newValue) {
        if (!(this.computedTrueOrFalse || this.computedOption)) {
          return;
        }

        if (!newValue.length) {
          return;
        }

        if (this.isCorrectTrueOrFalse(newValue) || this.isCorrectOption(newValue)) {
          this.correct = true;
          return;
        }

        this.correct = false;
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
    },
    computedCanUpdateCorrect: function computedCanUpdateCorrect() {
      return this.autoMark && !this.removed || this.answering;
    },
    computedClasses: function computedClasses() {
      return this.$vnode.data.staticClass ? this.$vnode.data.staticClass : '';
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
    },
    clickedUpdateCorrect: function clickedUpdateCorrect() {
      if (!this.answering) {
        return;
      }

      this.correct = !this.correct;
    },
    nodeClassHas: function nodeClassHas(text) {
      return this.computedClasses.includes(text);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionAnsweringBadge.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionAnsweringBadge.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _forms_AnswerForm_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../forms/AnswerForm.vue */ "./resources/js/components/forms/AnswerForm.vue");
/* harmony import */ var _MainCheckbox_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../MainCheckbox.vue */ "./resources/js/components/MainCheckbox.vue");
/* harmony import */ var _AnswerBadge_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./AnswerBadge.vue */ "./resources/js/components/dashboard/AnswerBadge.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    AnswerForm: _forms_AnswerForm_vue__WEBPACK_IMPORTED_MODULE_0__.default,
    MainCheckbox: _MainCheckbox_vue__WEBPACK_IMPORTED_MODULE_1__.default,
    AnswerBadge: _AnswerBadge_vue__WEBPACK_IMPORTED_MODULE_2__.default
  },
  props: {
    question: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    answer: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    questionAnswer: {
      type: Object,
      "default": function _default() {
        return null;
      }
    }
  },
  data: function data() {
    return {
      showHint: false,
      show: true,
      mode: ''
    };
  },
  watch: {
    questionAnswer: {
      immediate: true,
      handler: function handler(newValue) {
        if (newValue) {
          this.show = false;
        }

        if (!newValue) {
          this.show = true;
        }
      }
    }
  },
  computed: {},
  methods: {
    answered: function answered(data) {
      if (!['update', 'delete'].includes(this.mode)) {
        data.questionId = this.question.id;
      }

      if (data.close) {
        this.show = false;
        return;
      }

      if (this.mode === 'update' || data.type === 'update') {
        data = {
          type: data.type.length ? data.type : this.mode,
          answer: this.questionAnswer,
          newAnswerData: data
        };
      }

      this.$emit('answered', data);
      this.mode = '';
    },
    clickedButton: function clickedButton(data) {
      if (!this.questionAnswer) {
        return;
      }

      if (data === 'update') {
        this.show = false;
        return;
      }

      this.mode = data.type;

      if (data.type === 'update') {
        this.show = true;
        return;
      }

      this.answered({
        type: data.type,
        answer: this.questionAnswer
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AnswerForm.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AnswerForm.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _TextTextarea_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../TextTextarea.vue */ "./resources/js/components/TextTextarea.vue");
/* harmony import */ var _TextInput_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../TextInput.vue */ "./resources/js/components/TextInput.vue");
/* harmony import */ var _dashboard_PossibleAnswerBadge_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../dashboard/PossibleAnswerBadge.vue */ "./resources/js/components/dashboard/PossibleAnswerBadge.vue");
/* harmony import */ var _specials_DroppableComponent_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../specials/DroppableComponent.vue */ "./resources/js/components/specials/DroppableComponent.vue");
/* harmony import */ var _NumberInput_vue__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../NumberInput.vue */ "./resources/js/components/NumberInput.vue");
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
    DroppableComponent: _specials_DroppableComponent_vue__WEBPACK_IMPORTED_MODULE_3__.default,
    PossibleAnswerBadge: _dashboard_PossibleAnswerBadge_vue__WEBPACK_IMPORTED_MODULE_2__.default,
    TextInput: _TextInput_vue__WEBPACK_IMPORTED_MODULE_1__.default,
    TextTextarea: _TextTextarea_vue__WEBPACK_IMPORTED_MODULE_0__.default,
    NumberInput: _NumberInput_vue__WEBPACK_IMPORTED_MODULE_4__.default
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
    },
    providedAnswer: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    currentAnswer: {
      type: Object,
      "default": function _default() {
        return null;
      }
    }
  },
  data: function data() {
    return {
      answer: {
        assessmentSectionId: null,
        questionId: null,
        answer: '',
        file: null,
        possibleAnswerIds: []
      },
      answerPossibleAnswers: []
    };
  },
  watch: {
    possibleAnswers: {
      immediate: true,
      handler: function handler(newValue) {
        if (this.computedDrag) {
          this.answerPossibleAnswers = this.arrangedPossibleAnswersByProvided();
          return;
        }

        this.answerPossibleAnswers = newValue;
      }
    },
    providedAnswer: {
      immediate: true,
      handler: function handler(newValue) {
        if (!newValue) {
          this.clearAnswer();
          return;
        }

        this.answer.answer = newValue.answer;
        this.answer.assessmentSectionId = newValue.assessmentSectionId;
        this.answer.file = newValue.file;
        this.answer.possibleAnswerIds = newValue.possibleAnswerIds;
        this.answer.questionId = newValue.questionId;
      }
    },
    "answer.answer": function answerAnswer(newValue) {
      if (this.currentAnswer) return;

      if (newValue && this.computedTypingAnswerType) {
        this.emitAnswer();
      }
    },
    "answer.file": function answerFile(newValue) {
      if (this.currentAnswer) return;

      if (newValue && this.computedFileAnswerType) {
        this.emitAnswer();
      }
    }
  },
  computed: {
    computedDrag: function computedDrag() {
      return ['arrange', 'flow'].includes(this.computedAnswerType);
    },
    computedSelectable: function computedSelectable() {
      return ['option', 'true_false'].includes(this.computedAnswerType);
    },
    computedTypingAnswerType: function computedTypingAnswerType() {
      return ['long_answer', 'short_answer', 'number'].includes(this.computedAnswerType);
    },
    computedFileAnswerType: function computedFileAnswerType() {
      return ['file', 'image', 'video', 'audio'].includes(this.computedAnswerType);
    },
    computedAnswerType: function computedAnswerType() {
      return this.answerType ? this.answerType.toLowerCase() : '';
    },
    computedPossibleAnswerIds: function computedPossibleAnswerIds() {
      return this.answerPossibleAnswers.map(function (possibleAnswer) {
        return possibleAnswer.id;
      });
    },
    computedCorrectPossibleAnswers: function computedCorrectPossibleAnswers() {
      var _this = this;

      return this.providedAnswer ? this.possibleAnswers.filter(function (possibleAnswer) {
        return _this.providedAnswer.possibleAnswerIds.includes(possibleAnswer.id);
      }) : [];
    },
    computedHasData: function computedHasData() {
      return this.answer.answer.length || this.answer.possibleAnswerIds.length || this.answer.file;
    }
  },
  methods: {
    movePossibleAnswer: function movePossibleAnswer(data) {
      if (data.fromPossibleAnswerIndex + 1 === this.answerPossibleAnswers.length && data.toPossibleAnswerIndex === undefined) {
        return;
      }

      var fromPossibleAnswer = this.answerPossibleAnswers.splice(data.fromPossibleAnswerIndex, 1)[0];

      if (data.toPossibleAnswerIndex === undefined) {
        this.answerPossibleAnswers.push(fromPossibleAnswer);
        return;
      }

      if (data.toPossibleAnswerIndex === 0) {
        this.answerPossibleAnswers.unshift(fromPossibleAnswer);
        return;
      }

      this.answerPossibleAnswers.splice(data.toPossibleAnswerIndex, 0, fromPossibleAnswer);
      this.$nextTick(this.addPossibleAnswer(null));
    },
    arrangedPossibleAnswersByProvided: function arrangedPossibleAnswersByProvided() {
      var _this2 = this;

      if (!this.providedAnswer || this.providedAnswer.possibleAnswerIds.length > 1) {
        return this.possibleAnswers;
      }

      var arrangedPossibleAnswers = [];
      this.providedAnswer.possibleAnswerIds.forEach(function (possibleAnswerId) {
        arrangedPossibleAnswers.push(_this2.possibleAnswers.find(function (possibleAnswer) {
          possibleAnswer.id == possibleAnswerId;
        }));
      });
      return arrangedPossibleAnswers;
    },
    possibleAnswerIsCorrect: function possibleAnswerIsCorrect(data) {
      this.addPossibleAnswer(data);
    },
    possibleAnswerIsWrong: function possibleAnswerIsWrong(data) {
      this.removePossibleAnswer(data);
    },
    removePossibleAnswer: function removePossibleAnswer(data) {
      var index = this.answer.possibleAnswers.findIndex(function (possibleAnswer) {
        return possibleAnswer.id == data.id;
      });

      if (index === -1) {
        return;
      }

      this.answer.possibleAnswers.splice(index, 1);
      if (this.currentAnswer) return;
      this.emitAnswer();
    },
    emitAnswer: function emitAnswer() {
      this.$emit('answered', this.answer);
    },
    addPossibleAnswer: function addPossibleAnswer(data) {
      this.answer.possibleAnswerIds = [];

      if (this.computedSelectable && data) {
        this.answer.possibleAnswerIds = [data.id];
        if (this.currentAnswer) return;
        this.emitAnswer();
        return;
      }

      if (data) {
        return;
      }

      this.answer.possibleAnswerIds = this.answerPossibleAnswers;
      if (this.currentAnswer) return;
      this.emitAnswer();
    },
    clearAnswer: function clearAnswer() {
      this.answer.assessmentSectionId = null, this.answer.questionId = null, this.answer.answer = '', this.answer.file = null, this.answer.possibleAnswerIds = [];
    },
    clickedButton: function clickedButton(text) {
      if (text === 'update') {
        this.$emit('answered', _objectSpread({
          type: text
        }, this.answer));
        return;
      }

      this.clearAnswer();
      this.$emit('answered', {
        close: true
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _dashboard_AssessmentSectionAnsweringBadge_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../dashboard/AssessmentSectionAnsweringBadge.vue */ "./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue");
/* harmony import */ var _NavigationComponent_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../NavigationComponent.vue */ "./resources/js/components/NavigationComponent.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    AssessmentSectionAnsweringBadge: _dashboard_AssessmentSectionAnsweringBadge_vue__WEBPACK_IMPORTED_MODULE_0__.default,
    NavigationComponent: _NavigationComponent_vue__WEBPACK_IMPORTED_MODULE_1__.default
  },
  props: {
    assessment: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    numberOfQuestions: {
      type: Number,
      "default": 0
    },
    work: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    computedAccount: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    answers: {
      type: Array,
      "default": function _default() {
        return [];
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

        this.initiate();
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
    },
    computedIsFirstSection: function computedIsFirstSection() {
      var _this$currentAssessme;

      return this.firstAssessmentSectionId === ((_this$currentAssessme = this.currentAssessmentSection) === null || _this$currentAssessme === void 0 ? void 0 : _this$currentAssessme.id);
    },
    computedIsLastSection: function computedIsLastSection() {
      var _this$currentAssessme2;

      return this.lastAssessmentSectionId === ((_this$currentAssessme2 = this.currentAssessmentSection) === null || _this$currentAssessme2 === void 0 ? void 0 : _this$currentAssessme2.id);
    },
    computedWorkAnswers: function computedWorkAnswers() {
      var _this = this;

      return this.work ? this.work.answers.filter(function (answer) {
        var _this$currentAssessme3;

        return answer.assessmentSectionId == ((_this$currentAssessme3 = _this.currentAssessmentSection) === null || _this$currentAssessme3 === void 0 ? void 0 : _this$currentAssessme3.id);
      }) : [];
    },
    computedPossiblyDone: function computedPossiblyDone() {
      return this.work.answers.filter(function (answer) {
        return !!answer;
      }).length === this.numberOfQuestions;
    }
  },
  methods: {
    initiate: function initiate() {
      this.firstAssessmentSectionId = this.assessment.assessmentSections[0].id;
      this.lastAssessmentSectionId = this.assessment.assessmentSections[this.assessment.assessmentSections.length - 1].id;
      this.clickedSectionNavigator('next');
    },
    initiatedAssessmentSection: function initiatedAssessmentSection(section) {
      this.$emit('initiated', section);
    },
    clickedSectionNavigator: function clickedSectionNavigator(text) {
      if (text === 'done') {
        this.$emit('goToStep', 0);
        return;
      }

      if (text === 'previous') {
        this.goToPrevious();
        return;
      }

      this.goToNext();
    },
    goTo: function goTo(number) {
      this.currentAssessmentSection = this.assessment.assessmentSections[this.computedCurrentAssessmentSectionIndex + number];
    },
    goToNext: function goToNext() {
      this.goTo(1);
    },
    goToPrevious: function goToPrevious() {
      this.goTo(-1);
    },
    answered: function answered(data) {
      this.$emit('answered', data);
    },
    fewMinutesMore: function fewMinutesMore(data) {
      this.$emit('fewMinutesMore', data);
    },
    noTimeLeft: function noTimeLeft(data) {
      this.$emit('noTimeLeft', data);
    },
    clickedPopupResponse: function clickedPopupResponse(data) {
      this.$emit('clickedResponse', data);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/PartialMark.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/PartialMark.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({});

/***/ }),

/***/ "./resources/js/mixins/Files.mixin.js":
/*!********************************************!*\
  !*** ./resources/js/mixins/Files.mixin.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  computed: {
    computedFiles: function computedFiles() {
      var _this$computedItemabl, _this$computedItemabl2, _this$computedItemabl3, _this$computedItemabl4;

      var files = [];

      if ((_this$computedItemabl = this.computedItemable) !== null && _this$computedItemabl !== void 0 && _this$computedItemabl.files) {
        files.push.apply(files, _toConsumableArray(this.computedItemable.files));
      }

      if ((_this$computedItemabl2 = this.computedItemable) !== null && _this$computedItemabl2 !== void 0 && _this$computedItemabl2.videos) {
        files.push.apply(files, _toConsumableArray(this.computedItemable.videos));
      }

      if ((_this$computedItemabl3 = this.computedItemable) !== null && _this$computedItemabl3 !== void 0 && _this$computedItemabl3.audios) {
        files.push.apply(files, _toConsumableArray(this.computedItemable.audios));
      }

      if ((_this$computedItemabl4 = this.computedItemable) !== null && _this$computedItemabl4 !== void 0 && _this$computedItemabl4.images) {
        files.push.apply(files, _toConsumableArray(this.computedItemable.images));
      }

      return files;
    }
  }
});

/***/ }),

/***/ "./resources/js/mixins/Timing.mixin.js":
/*!*********************************************!*\
  !*** ./resources/js/mixins/Timing.mixin.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      timingItemLocked: false,
      timingItemWait: false,
      timingItemHasFewTimeLeft: null,
      timingItemTimeLeft: false,
      timingItemTime: null,
      noTimeLeftHandled: false
    };
  },
  watch: {
    "computedItemable.timer": function computedItemableTimer(newValue) {
      this.startTimer();
    }
  },
  computed: {
    computedTimingShow: function computedTimingShow() {
      return !this.timingItemLocked && !this.timingItemWait;
    }
  },
  methods: _objectSpread(_objectSpread({}, (0,vuex__WEBPACK_IMPORTED_MODULE_1__.mapActions)(['dashboard/addTime'])), {}, {
    startTimer: function startTimer() {
      if (!this.computedItemable.duration) {
        return;
      }

      if (!this.computedItemable.timer) {
        this.getTimer();
        return;
      }

      this.setTimeObject();
      this.setTimingData();

      if (this.timingItemTime.isPast()) {
        this.noTimeLeft();
        return;
      }

      this.timeItemable();
    },
    noTimeLeft: function noTimeLeft(data) {
      if (this.noTimeLeftHandled) {
        return;
      }

      this.timingItemLocked = true;
      this.timingItemWait = false;
      this.$emit('noTimeLeft', _objectSpread({}, this.computedItem));

      if ('handleNoTimeLeft' in this) {
        this.handleNoTimeLeft(data);
      }
    },
    fewMinutesMore: function fewMinutesMore(data) {
      this.$emit('fewMinutesMore', _objectSpread({}, this.computedItem));

      if ('handleFewMinutesMore' in this) {
        this.handleFewMinutesMore(data);
      }
    },
    setTimeObject: function setTimeObject() {
      if (this.timingItemTime) {
        return;
      }

      var time = {
        startDate: new Date(this.computedItemable.timer.startTime),
        endDate: new Date(this.computedItemable.timer.endTime),
        floatMinutesleft: function floatMinutesleft() {
          return (this.endDate - new Date()) / (1000 * 60);
        },
        timeFormat: function timeFormat(number) {
          if (number > 9) {
            return number;
          }

          if (number < 0) {
            number = 0;
          }

          return "0".concat(number);
        },
        currentHours: function currentHours() {
          return this.timeFormat(Math.floor(this.floatMinutesleft() / 60));
        },
        currentMinutes: function currentMinutes() {
          return this.timeFormat(Math.floor(this.floatMinutesleft() > 59 ? 59 : this.floatMinutesleft()));
        },
        currentSeconds: function currentSeconds() {
          var floatSeconds = (this.floatMinutesleft() - Math.floor(this.floatMinutesleft())) * 60;
          return this.timeFormat(Math.floor(floatSeconds));
        },
        currentTimeLeft: function currentTimeLeft() {
          return "".concat(this.currentHours(), ":").concat(this.currentMinutes(), ":").concat(this.currentSeconds());
        },
        isPast: function isPast() {
          return new Date() > this.endDate;
        },
        hasFewTimeLeft: function hasFewTimeLeft() {
          if (this.duration) {
            return false;
          }

          if (5 >= this.timingItemTime.floatMinutesleft()) {
            return true;
          }

          return false;
        }
      };
      time['duration'] = Math.floor((time.endDate - time.startDate) / (1000 * 60));
      this.timingItemTime = time;
    },
    getTimer: function getTimer() {
      var _this = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee() {
        var data, response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                if (_this.computedAccount) {
                  _context.next = 3;
                  break;
                }

                _this.issueDangerAlert({
                  message: "sorry \uD83D\uDE1E, there is no valid account associated with this ".concat(_this.computedItem.item)
                });

                return _context.abrupt("return");

              case 3:
                _this.timingItemWait = true;
                data = _objectSpread(_objectSpread({
                  time: new Date().toISOString()
                }, _this.computedItem), {}, {
                  accountId: _this.computedAccount.accountId,
                  account: _this.computedAccount.account
                });
                _context.next = 7;
                return _this['dashboard/addTime']({
                  item: _this.computedItemable,
                  data: data
                });

              case 7:
                response = _context.sent;

                if (response.status) {// this.issueSuccessAlert({message: ``})
                }

                if (!response.status && 'issueDangerAlertForResponse' in _this) {
                  _this.issueDangerAlertForResponse(response);
                }

                _this.timingItemWait = false;

              case 11:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },
    setTimingData: function setTimingData() {
      this.timingItemTimeLeft = this.timingItemTime.currentTimeLeft();
      this.timingItemHasFewTimeLeft = this.timingItemTime.hasFewTimeLeft();
    },
    timeItemable: function timeItemable() {
      var _this2 = this;

      this.timingItemWait = false;
      this.timingItemLocked = false;
      var interval = setInterval(function () {
        _this2.setTimingData();

        if (_this2.timingItemTime.floatMinutesleft() <= -2) {
          _this2.noTimeLeft();

          clearInterval(interval);
          return;
        }
      }, 30000);
    }
  })
});

/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".small-msg[data-v-daab0f30] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.files-preview-backend-wrapper .message[data-v-daab0f30] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n}\n.files-preview-backend-wrapper .main[data-v-daab0f30],\n.files-preview-backend-wrapper .removed[data-v-daab0f30] {\n  width: 90%;\n  max-width: 400px;\n  margin: 10px auto;\n  max-height: 400px;\n  display: flex;\n  flex-wrap: nowrap;\n  overflow-y: hidden;\n  overflow-x: auto;\n  padding: 10px;\n  align-items: center;\n}\n.files-preview-backend-wrapper .main .single-file-preview[data-v-daab0f30],\n.files-preview-backend-wrapper .removed .single-file-preview[data-v-daab0f30] {\n  min-width: 100%;\n  max-width: 100%;\n  position: relative;\n  padding: 5px;\n  padding-bottom: 0;\n  background: mintcream;\n  margin-right: 10px;\n}\n.files-preview-backend-wrapper .main .single-file-preview video[data-v-daab0f30],\n.files-preview-backend-wrapper .main .single-file-preview img[data-v-daab0f30],\n.files-preview-backend-wrapper .removed .single-file-preview video[data-v-daab0f30],\n.files-preview-backend-wrapper .removed .single-file-preview img[data-v-daab0f30] {\n  width: 100%;\n  height: 100%;\n  -o-object-fit: contain;\n     object-fit: contain;\n  -o-object-position: center;\n     object-position: center;\n}\n.files-preview-backend-wrapper .main .single-file-preview audio[data-v-daab0f30],\n.files-preview-backend-wrapper .removed .single-file-preview audio[data-v-daab0f30] {\n  width: 100%;\n}\n.files-preview-backend-wrapper .main .remove[data-v-daab0f30],\n.files-preview-backend-wrapper .removed .remove[data-v-daab0f30] {\n  position: absolute;\n  z-index: 1;\n  top: 10px;\n  right: 10px;\n  padding: 5px;\n  font-size: 16px;\n  color: red;\n  cursor: pointer;\n}\n.files-preview-backend-wrapper .removed[data-v-daab0f30] {\n  background: red;\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".small-msg[data-v-49938287] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.main-checkbox-wrapper[data-v-49938287] {\n  display: flex;\n  align-items: center;\n  padding: 5px;\n  cursor: pointer;\n}\n.main-checkbox-wrapper .no-icon[data-v-49938287] {\n  width: 20px;\n  height: 20px;\n  border: 2px solid #16e9cd;\n  margin-right: 10px;\n  border-radius: 50%;\n  display: flex;\n  align-items: center;\n  justify-content: center;\n  padding: 10px;\n  font-size: 14px;\n  color: white;\n}\n.main-checkbox-wrapper .icon[data-v-49938287] {\n  background: #16e9cd;\n  -webkit-animation-name: icon-data-v-49938287;\n          animation-name: icon-data-v-49938287;\n  -webkit-animation-duration: 0.4s;\n          animation-duration: 0.4s;\n  -webkit-animation-timing-function: cubic-bezier(0.075, 0.82, 0.165, 1);\n          animation-timing-function: cubic-bezier(0.075, 0.82, 0.165, 1);\n}\n.main-checkbox-wrapper label[data-v-49938287] {\n  margin: 0;\n  font-weight: 450;\n  font-size: 16px;\n  cursor: pointer;\n  color: gray;\n}\n.main-checkbox-wrapper input[type=checkbox][data-v-49938287] {\n  display: none;\n}\n.main-checkbox-wrapper.small .no-icon[data-v-49938287] {\n  font-size: 9px;\n  padding: 7px;\n}\n.main-checkbox-wrapper.small label[data-v-49938287] {\n  font-size: 12px;\n}\n@media screen and (max-width: 800px) {\n.main-checkbox-wrapper .no-icon[data-v-49938287] {\n    width: 15px;\n    height: 15px;\n    font-size: 10px;\n    padding: 8px;\n}\n.main-checkbox-wrapper label[data-v-49938287] {\n    font-size: 14px;\n}\n}\n@-webkit-keyframes icon-data-v-49938287 {\nfrom {\n    transform: scale(0);\n    transform: rotateZ(75deg);\n}\nto {\n    transform: scale(1);\n    transform: rotateZ(0deg);\n}\n}\n@keyframes icon-data-v-49938287 {\nfrom {\n    transform: scale(0);\n    transform: rotateZ(75deg);\n}\nto {\n    transform: scale(1);\n    transform: rotateZ(0deg);\n}\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".small-msg[data-v-4b58166c] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.assessment-section-answering-badge .main-section[data-v-4b58166c] {\n  width: 100%;\n  background: aquamarine;\n  color: gray;\n  font-size: 14px;\n  padding: 5px;\n  margin: 0 0 10px;\n  text-transform: capitalize;\n}\n.assessment-section-answering-badge .instruction[data-v-4b58166c] {\n  background: white;\n  padding: 5px;\n  text-align: center;\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".small-msg[data-v-bbeb6d04] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.possible-answer-badge-wrapper[data-v-bbeb6d04] {\n  cursor: pointer;\n  padding: 0 5px;\n  border-radius: 10px;\n  margin: 5px auto;\n}\n.possible-answer-badge-wrapper .true-false[data-v-bbeb6d04] {\n  display: flex;\n  width: -webkit-fit-content;\n  width: -moz-fit-content;\n  width: fit-content;\n  align-items: center;\n  width: 100%;\n}\n.possible-answer-badge-wrapper .true-false .list-identifier[data-v-bbeb6d04] {\n  width: 10px;\n  height: 10px;\n  border-radius: 100%;\n  background: gray;\n  margin-right: 10px;\n  position: relative;\n}\n.possible-answer-badge-wrapper .true-false .checkbox[data-v-bbeb6d04] {\n  margin-left: auto;\n  margin-right: -10px;\n}\n.possible-answer-badge-wrapper .option[data-v-bbeb6d04] {\n  display: flex;\n  width: -webkit-fit-content;\n  width: -moz-fit-content;\n  width: fit-content;\n  align-items: center;\n  width: 100%;\n}\n.possible-answer-badge-wrapper .option .list-identifier[data-v-bbeb6d04] {\n  color: gray;\n  margin-right: 10px;\n  position: relative;\n}\n.possible-answer-badge-wrapper .option .item[data-v-bbeb6d04] {\n  min-width: -webkit-fit-content;\n  min-width: -moz-fit-content;\n  min-width: fit-content;\n}\n.possible-answer-badge-wrapper .option .checkbox[data-v-bbeb6d04] {\n  margin-left: auto;\n  margin-right: -10px;\n}\n.possible-answer-badge-wrapper .arrange[data-v-bbeb6d04] {\n  display: flex;\n  align-items: center;\n}\n.possible-answer-badge-wrapper .arrange .list-identifier[data-v-bbeb6d04] {\n  width: 10px;\n  margin-right: 5px;\n  height: 2px;\n  background: black;\n  position: relative;\n}\n.possible-answer-badge-wrapper .arrange .list-identifier[data-v-bbeb6d04]::before, .possible-answer-badge-wrapper .arrange .list-identifier[data-v-bbeb6d04]::after {\n  content: \"\";\n  width: 12px;\n  height: 2px;\n  background: gray;\n  position: absolute;\n  left: 0;\n}\n.possible-answer-badge-wrapper .arrange .list-identifier[data-v-bbeb6d04]::before {\n  transform: rotateZ(15deg);\n  top: -5px;\n}\n.possible-answer-badge-wrapper .arrange .list-identifier[data-v-bbeb6d04]::after {\n  top: 5px;\n  transform: rotateZ(-15deg);\n}\n.possible-answer-badge-wrapper .flow[data-v-bbeb6d04] {\n  display: block;\n  text-align: center;\n}\n.possible-answer-badge-wrapper .flow .list-identifier[data-v-bbeb6d04] {\n  width: 2px;\n  margin-right: 5px;\n  height: 30px;\n  background: black;\n  position: relative;\n  margin: 0 auto;\n}\n.possible-answer-badge-wrapper .flow .list-identifier[data-v-bbeb6d04]::before, .possible-answer-badge-wrapper .flow .list-identifier[data-v-bbeb6d04]::after {\n  content: \"\";\n  width: 12px;\n  height: 2px;\n  background: gray;\n  position: absolute;\n  bottom: 1px;\n  background: brown;\n}\n.possible-answer-badge-wrapper .flow .list-identifier[data-v-bbeb6d04]::before {\n  transform: rotateZ(55deg);\n  left: -8px;\n}\n.possible-answer-badge-wrapper .flow .list-identifier[data-v-bbeb6d04]::after {\n  transform: rotateZ(125deg);\n  left: -2px;\n}\n.possible-answer-badge-wrapper[data-v-bbeb6d04]:hover {\n  background: aliceblue;\n  transition-timing-function: cubic-bezier(0.86, 0, 0.07, 1);\n  transition-property: color, background-color;\n  transition-duration: 0.4s;\n}\n.possible-answer-badge-wrapper.no-drag[data-v-bbeb6d04]:hover {\n  background: inherit;\n}\n.possible-answer-badge-wrapper.removed[data-v-bbeb6d04] {\n  box-shadow: 0 0 1px gray;\n}\n.possible-answer-badge-wrapper.removed .option[data-v-bbeb6d04], .possible-answer-badge-wrapper.removed .true-false[data-v-bbeb6d04], .possible-answer-badge-wrapper.removed .flow[data-v-bbeb6d04], .possible-answer-badge-wrapper.removed .arrange[data-v-bbeb6d04] {\n  display: flex;\n  justify-content: center;\n}\n.possible-answer-badge-wrapper.correct[data-v-bbeb6d04] {\n  background: darkgreen;\n  color: white;\n  transition-timing-function: cubic-bezier(0.86, 0, 0.07, 1);\n  transition-property: color, background-color;\n  transition-duration: 0.8s;\n}\n.possible-answer-badge-wrapper.correct .option .list-identifier[data-v-bbeb6d04] {\n  color: white;\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".small-msg[data-v-10862390] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.assessment-section-answering-form .main-section[data-v-10862390] {\n  width: 100%;\n  background: aquamarine;\n  color: gray;\n  font-size: 14px;\n  padding: 5px;\n  margin: 0 0 10px;\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".small-msg[data-v-6fc3c8bc] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.work-answering-form-wrapper .welcome-form[data-v-6fc3c8bc] {\n  position: relative;\n}\n.work-answering-form-wrapper .welcome-form .loading[data-v-6fc3c8bc] {\n  position: sticky;\n  width: 100%;\n  text-align: center;\n  top: 10px;\n  z-index: 1;\n  top: 49%;\n}\n.work-answering-form-wrapper .welcome-form .section[data-v-6fc3c8bc] {\n  font-size: 12px;\n  font-weight: 500;\n  border-bottom: 1px solid gray;\n  margin: 30px 0 10px;\n  text-transform: capitalize;\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/PartialMark.vue?vue&type=style&index=0&id=9588cf38&lang=scss&scoped=true&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/PartialMark.vue?vue&type=style&index=0&id=9588cf38&lang=scss&scoped=true& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".small-msg[data-v-9588cf38] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.cls-1[data-v-9588cf38] {\n  fill: transparent;\n  stroke: currentColor;\n  stroke-miterlimit: 10;\n  stroke-width: 27px;\n  stroke-linecap: round;\n  display: inline-block;\n  font-size: inherit;\n  height: 1em;\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_style_index_0_id_daab0f30_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_style_index_0_id_daab0f30_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default, options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_style_index_0_id_daab0f30_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default.locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_style_index_0_id_49938287_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_style_index_0_id_49938287_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default, options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_style_index_0_id_49938287_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default.locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true&":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_style_index_0_id_4b58166c_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_style_index_0_id_4b58166c_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default, options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_style_index_0_id_4b58166c_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default.locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true&":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_PossibleAnswerBadge_vue_vue_type_style_index_0_id_bbeb6d04_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_PossibleAnswerBadge_vue_vue_type_style_index_0_id_bbeb6d04_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default, options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_PossibleAnswerBadge_vue_vue_type_style_index_0_id_bbeb6d04_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default.locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringForm_vue_vue_type_style_index_0_id_10862390_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringForm_vue_vue_type_style_index_0_id_10862390_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default, options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringForm_vue_vue_type_style_index_0_id_10862390_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default.locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkAnsweringForm_vue_vue_type_style_index_0_id_6fc3c8bc_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkAnsweringForm_vue_vue_type_style_index_0_id_6fc3c8bc_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default, options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkAnsweringForm_vue_vue_type_style_index_0_id_6fc3c8bc_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default.locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/PartialMark.vue?vue&type=style&index=0&id=9588cf38&lang=scss&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/PartialMark.vue?vue&type=style&index=0&id=9588cf38&lang=scss&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_PartialMark_vue_vue_type_style_index_0_id_9588cf38_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PartialMark.vue?vue&type=style&index=0&id=9588cf38&lang=scss&scoped=true& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/PartialMark.vue?vue&type=style&index=0&id=9588cf38&lang=scss&scoped=true&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_PartialMark_vue_vue_type_style_index_0_id_9588cf38_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default, options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_PartialMark_vue_vue_type_style_index_0_id_9588cf38_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default.locals || {});

/***/ }),

/***/ "./resources/js/components/EditDeleteButtons.vue":
/*!*******************************************************!*\
  !*** ./resources/js/components/EditDeleteButtons.vue ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _EditDeleteButtons_vue_vue_type_template_id_4cdac491_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./EditDeleteButtons.vue?vue&type=template&id=4cdac491&scoped=true& */ "./resources/js/components/EditDeleteButtons.vue?vue&type=template&id=4cdac491&scoped=true&");
/* harmony import */ var _EditDeleteButtons_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./EditDeleteButtons.vue?vue&type=script&lang=js& */ "./resources/js/components/EditDeleteButtons.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _EditDeleteButtons_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _EditDeleteButtons_vue_vue_type_template_id_4cdac491_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _EditDeleteButtons_vue_vue_type_template_id_4cdac491_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "4cdac491",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/EditDeleteButtons.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/FilesPreviewBackend.vue":
/*!*********************************************************!*\
  !*** ./resources/js/components/FilesPreviewBackend.vue ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _FilesPreviewBackend_vue_vue_type_template_id_daab0f30_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./FilesPreviewBackend.vue?vue&type=template&id=daab0f30&scoped=true& */ "./resources/js/components/FilesPreviewBackend.vue?vue&type=template&id=daab0f30&scoped=true&");
/* harmony import */ var _FilesPreviewBackend_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./FilesPreviewBackend.vue?vue&type=script&lang=js& */ "./resources/js/components/FilesPreviewBackend.vue?vue&type=script&lang=js&");
/* harmony import */ var _FilesPreviewBackend_vue_vue_type_style_index_0_id_daab0f30_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true& */ "./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__.default)(
  _FilesPreviewBackend_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _FilesPreviewBackend_vue_vue_type_template_id_daab0f30_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _FilesPreviewBackend_vue_vue_type_template_id_daab0f30_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "daab0f30",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/FilesPreviewBackend.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/MainCheckbox.vue":
/*!**************************************************!*\
  !*** ./resources/js/components/MainCheckbox.vue ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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

/***/ "./resources/js/components/NavigationButton.vue":
/*!******************************************************!*\
  !*** ./resources/js/components/NavigationButton.vue ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _NavigationButton_vue_vue_type_template_id_3da52dd1_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NavigationButton.vue?vue&type=template&id=3da52dd1&scoped=true& */ "./resources/js/components/NavigationButton.vue?vue&type=template&id=3da52dd1&scoped=true&");
/* harmony import */ var _NavigationButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./NavigationButton.vue?vue&type=script&lang=js& */ "./resources/js/components/NavigationButton.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _NavigationButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _NavigationButton_vue_vue_type_template_id_3da52dd1_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _NavigationButton_vue_vue_type_template_id_3da52dd1_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "3da52dd1",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/NavigationButton.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/NavigationComponent.vue":
/*!*********************************************************!*\
  !*** ./resources/js/components/NavigationComponent.vue ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _NavigationComponent_vue_vue_type_template_id_34a84aa4_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NavigationComponent.vue?vue&type=template&id=34a84aa4&scoped=true& */ "./resources/js/components/NavigationComponent.vue?vue&type=template&id=34a84aa4&scoped=true&");
/* harmony import */ var _NavigationComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./NavigationComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/NavigationComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _NavigationComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _NavigationComponent_vue_vue_type_template_id_34a84aa4_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _NavigationComponent_vue_vue_type_template_id_34a84aa4_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "34a84aa4",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/NavigationComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/dashboard/AnswerBadge.vue":
/*!***********************************************************!*\
  !*** ./resources/js/components/dashboard/AnswerBadge.vue ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _AnswerBadge_vue_vue_type_template_id_142e45a2_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AnswerBadge.vue?vue&type=template&id=142e45a2&scoped=true& */ "./resources/js/components/dashboard/AnswerBadge.vue?vue&type=template&id=142e45a2&scoped=true&");
/* harmony import */ var _AnswerBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AnswerBadge.vue?vue&type=script&lang=js& */ "./resources/js/components/dashboard/AnswerBadge.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _AnswerBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _AnswerBadge_vue_vue_type_template_id_142e45a2_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _AnswerBadge_vue_vue_type_template_id_142e45a2_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "142e45a2",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/dashboard/AnswerBadge.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue":
/*!*******************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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

/***/ "./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue":
/*!*********************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue ***!
  \*********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _AssessmentSectionInformationBadge_vue_vue_type_template_id_7b88a064_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AssessmentSectionInformationBadge.vue?vue&type=template&id=7b88a064&scoped=true& */ "./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=template&id=7b88a064&scoped=true&");
/* harmony import */ var _AssessmentSectionInformationBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AssessmentSectionInformationBadge.vue?vue&type=script&lang=js& */ "./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _AssessmentSectionInformationBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _AssessmentSectionInformationBadge_vue_vue_type_template_id_7b88a064_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _AssessmentSectionInformationBadge_vue_vue_type_template_id_7b88a064_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "7b88a064",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/dashboard/AssessmentSectionInformationBadge.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/dashboard/PossibleAnswerBadge.vue":
/*!*******************************************************************!*\
  !*** ./resources/js/components/dashboard/PossibleAnswerBadge.vue ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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

/***/ "./resources/js/components/specials/PartialMark.vue":
/*!**********************************************************!*\
  !*** ./resources/js/components/specials/PartialMark.vue ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _PartialMark_vue_vue_type_template_id_9588cf38_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./PartialMark.vue?vue&type=template&id=9588cf38&scoped=true& */ "./resources/js/components/specials/PartialMark.vue?vue&type=template&id=9588cf38&scoped=true&");
/* harmony import */ var _PartialMark_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./PartialMark.vue?vue&type=script&lang=js& */ "./resources/js/components/specials/PartialMark.vue?vue&type=script&lang=js&");
/* harmony import */ var _PartialMark_vue_vue_type_style_index_0_id_9588cf38_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./PartialMark.vue?vue&type=style&index=0&id=9588cf38&lang=scss&scoped=true& */ "./resources/js/components/specials/PartialMark.vue?vue&type=style&index=0&id=9588cf38&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__.default)(
  _PartialMark_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _PartialMark_vue_vue_type_template_id_9588cf38_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _PartialMark_vue_vue_type_template_id_9588cf38_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "9588cf38",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/specials/PartialMark.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/EditDeleteButtons.vue?vue&type=script&lang=js&":
/*!********************************************************************************!*\
  !*** ./resources/js/components/EditDeleteButtons.vue?vue&type=script&lang=js& ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditDeleteButtons_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./EditDeleteButtons.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/EditDeleteButtons.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditDeleteButtons_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/FilesPreviewBackend.vue?vue&type=script&lang=js&":
/*!**********************************************************************************!*\
  !*** ./resources/js/components/FilesPreviewBackend.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./FilesPreviewBackend.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/MainCheckbox.vue?vue&type=script&lang=js&":
/*!***************************************************************************!*\
  !*** ./resources/js/components/MainCheckbox.vue?vue&type=script&lang=js& ***!
  \***************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MainCheckbox.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/NavigationButton.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/js/components/NavigationButton.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NavigationButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./NavigationButton.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationButton.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NavigationButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/NavigationComponent.vue?vue&type=script&lang=js&":
/*!**********************************************************************************!*\
  !*** ./resources/js/components/NavigationComponent.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NavigationComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./NavigationComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NavigationComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/dashboard/AnswerBadge.vue?vue&type=script&lang=js&":
/*!************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AnswerBadge.vue?vue&type=script&lang=js& ***!
  \************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AnswerBadge.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerBadge.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionAnsweringBadge.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionInformationBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionInformationBadge.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionInformationBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DroppableComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./DroppableComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/DroppableComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DroppableComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/specials/PartialMark.vue?vue&type=script&lang=js&":
/*!***********************************************************************************!*\
  !*** ./resources/js/components/specials/PartialMark.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PartialMark_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PartialMark.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/PartialMark.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PartialMark_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true&":
/*!*******************************************************************************************************************!*\
  !*** ./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true& ***!
  \*******************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_style_index_0_id_daab0f30_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader/dist/cjs.js!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true&");


/***/ }),

/***/ "./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true&":
/*!************************************************************************************************************!*\
  !*** ./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true& ***!
  \************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_style_index_0_id_49938287_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader/dist/cjs.js!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true&");


/***/ }),

/***/ "./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true&":
/*!*****************************************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true& ***!
  \*****************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_style_index_0_id_4b58166c_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader/dist/cjs.js!../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=style&index=0&id=4b58166c&lang=scss&scoped=true&");


/***/ }),

/***/ "./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true&":
/*!*****************************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true& ***!
  \*****************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_PossibleAnswerBadge_vue_vue_type_style_index_0_id_bbeb6d04_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader/dist/cjs.js!../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=style&index=0&id=bbeb6d04&lang=scss&scoped=true&");


/***/ }),

/***/ "./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true&":
/*!************************************************************************************************************************************!*\
  !*** ./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true& ***!
  \************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringForm_vue_vue_type_style_index_0_id_10862390_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader/dist/cjs.js!../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionAnsweringForm.vue?vue&type=style&index=0&id=10862390&lang=scss&scoped=true&");


/***/ }),

/***/ "./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true&":
/*!***********************************************************************************************************************!*\
  !*** ./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true& ***!
  \***********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkAnsweringForm_vue_vue_type_style_index_0_id_6fc3c8bc_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader/dist/cjs.js!../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/WorkAnsweringForm.vue?vue&type=style&index=0&id=6fc3c8bc&lang=scss&scoped=true&");


/***/ }),

/***/ "./resources/js/components/specials/PartialMark.vue?vue&type=style&index=0&id=9588cf38&lang=scss&scoped=true&":
/*!********************************************************************************************************************!*\
  !*** ./resources/js/components/specials/PartialMark.vue?vue&type=style&index=0&id=9588cf38&lang=scss&scoped=true& ***!
  \********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_PartialMark_vue_vue_type_style_index_0_id_9588cf38_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader/dist/cjs.js!../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PartialMark.vue?vue&type=style&index=0&id=9588cf38&lang=scss&scoped=true& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/PartialMark.vue?vue&type=style&index=0&id=9588cf38&lang=scss&scoped=true&");


/***/ }),

/***/ "./resources/js/components/EditDeleteButtons.vue?vue&type=template&id=4cdac491&scoped=true&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/components/EditDeleteButtons.vue?vue&type=template&id=4cdac491&scoped=true& ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditDeleteButtons_vue_vue_type_template_id_4cdac491_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditDeleteButtons_vue_vue_type_template_id_4cdac491_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditDeleteButtons_vue_vue_type_template_id_4cdac491_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./EditDeleteButtons.vue?vue&type=template&id=4cdac491&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/EditDeleteButtons.vue?vue&type=template&id=4cdac491&scoped=true&");


/***/ }),

/***/ "./resources/js/components/FilesPreviewBackend.vue?vue&type=template&id=daab0f30&scoped=true&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/components/FilesPreviewBackend.vue?vue&type=template&id=daab0f30&scoped=true& ***!
  \****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_template_id_daab0f30_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_template_id_daab0f30_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_template_id_daab0f30_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./FilesPreviewBackend.vue?vue&type=template&id=daab0f30&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=template&id=daab0f30&scoped=true&");


/***/ }),

/***/ "./resources/js/components/MainCheckbox.vue?vue&type=template&id=49938287&scoped=true&":
/*!*********************************************************************************************!*\
  !*** ./resources/js/components/MainCheckbox.vue?vue&type=template&id=49938287&scoped=true& ***!
  \*********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_template_id_49938287_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_template_id_49938287_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_template_id_49938287_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MainCheckbox.vue?vue&type=template&id=49938287&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=template&id=49938287&scoped=true&");


/***/ }),

/***/ "./resources/js/components/NavigationButton.vue?vue&type=template&id=3da52dd1&scoped=true&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/NavigationButton.vue?vue&type=template&id=3da52dd1&scoped=true& ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NavigationButton_vue_vue_type_template_id_3da52dd1_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NavigationButton_vue_vue_type_template_id_3da52dd1_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NavigationButton_vue_vue_type_template_id_3da52dd1_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./NavigationButton.vue?vue&type=template&id=3da52dd1&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationButton.vue?vue&type=template&id=3da52dd1&scoped=true&");


/***/ }),

/***/ "./resources/js/components/NavigationComponent.vue?vue&type=template&id=34a84aa4&scoped=true&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/components/NavigationComponent.vue?vue&type=template&id=34a84aa4&scoped=true& ***!
  \****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NavigationComponent_vue_vue_type_template_id_34a84aa4_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NavigationComponent_vue_vue_type_template_id_34a84aa4_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NavigationComponent_vue_vue_type_template_id_34a84aa4_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./NavigationComponent.vue?vue&type=template&id=34a84aa4&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationComponent.vue?vue&type=template&id=34a84aa4&scoped=true&");


/***/ }),

/***/ "./resources/js/components/dashboard/AnswerBadge.vue?vue&type=template&id=142e45a2&scoped=true&":
/*!******************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AnswerBadge.vue?vue&type=template&id=142e45a2&scoped=true& ***!
  \******************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerBadge_vue_vue_type_template_id_142e45a2_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerBadge_vue_vue_type_template_id_142e45a2_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerBadge_vue_vue_type_template_id_142e45a2_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AnswerBadge.vue?vue&type=template&id=142e45a2&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerBadge.vue?vue&type=template&id=142e45a2&scoped=true&");


/***/ }),

/***/ "./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=template&id=4b58166c&scoped=true&":
/*!**************************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=template&id=4b58166c&scoped=true& ***!
  \**************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_template_id_4b58166c_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_template_id_4b58166c_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionAnsweringBadge_vue_vue_type_template_id_4b58166c_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionAnsweringBadge.vue?vue&type=template&id=4b58166c&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionAnsweringBadge.vue?vue&type=template&id=4b58166c&scoped=true&");


/***/ }),

/***/ "./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=template&id=7b88a064&scoped=true&":
/*!****************************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=template&id=7b88a064&scoped=true& ***!
  \****************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionInformationBadge_vue_vue_type_template_id_7b88a064_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionInformationBadge_vue_vue_type_template_id_7b88a064_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionInformationBadge_vue_vue_type_template_id_7b88a064_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionInformationBadge.vue?vue&type=template&id=7b88a064&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=template&id=7b88a064&scoped=true&");


/***/ }),

/***/ "./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=template&id=bbeb6d04&scoped=true&":
/*!**************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=template&id=bbeb6d04&scoped=true& ***!
  \**************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DroppableComponent_vue_vue_type_template_id_2b7b0408_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DroppableComponent_vue_vue_type_template_id_2b7b0408_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DroppableComponent_vue_vue_type_template_id_2b7b0408_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./DroppableComponent.vue?vue&type=template&id=2b7b0408&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/DroppableComponent.vue?vue&type=template&id=2b7b0408&scoped=true&");


/***/ }),

/***/ "./resources/js/components/specials/PartialMark.vue?vue&type=template&id=9588cf38&scoped=true&":
/*!*****************************************************************************************************!*\
  !*** ./resources/js/components/specials/PartialMark.vue?vue&type=template&id=9588cf38&scoped=true& ***!
  \*****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PartialMark_vue_vue_type_template_id_9588cf38_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PartialMark_vue_vue_type_template_id_9588cf38_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PartialMark_vue_vue_type_template_id_9588cf38_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PartialMark.vue?vue&type=template&id=9588cf38&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/PartialMark.vue?vue&type=template&id=9588cf38&scoped=true&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/EditDeleteButtons.vue?vue&type=template&id=4cdac491&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/EditDeleteButtons.vue?vue&type=template&id=4cdac491&scoped=true& ***!
  \*****************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "w-content" }, [
    _c(
      "div",
      {
        staticClass:
          "p-2 cursor-pointer hover:text-green-500 z-30 mr-1 text-gray-500",
        attrs: { "data-testid": "edit" },
        on: {
          click: function($event) {
            return _vm.clickedButton("edit")
          }
        }
      },
      [_c("font-awesome-icon", { attrs: { icon: ["fa", "pencil-alt"] } })],
      1
    ),
    _vm._v(" "),
    _c(
      "div",
      {
        staticClass: "p-2 cursor-pointer hover:text-red-500 z-30",
        attrs: { "data-testid": "delete" },
        on: {
          click: function($event) {
            return _vm.clickedButton("delete")
          }
        }
      },
      [_c("font-awesome-icon", { attrs: { icon: ["fa", "trash"] } })],
      1
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=template&id=daab0f30&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=template&id=daab0f30&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "files-preview-backend-wrapper" }, [
    _vm.mainMessage.length
      ? _c("div", { staticClass: "message" }, [
          _vm._v("\n        " + _vm._s(_vm.mainMessage) + "\n    ")
        ])
      : _vm._e(),
    _vm._v(" "),
    _c(
      "div",
      { staticClass: "main" },
      _vm._l(_vm.mainFiles, function(item) {
        return _c(
          "div",
          {
            key: item.type + "." + item.id,
            staticClass: "single-file-preview"
          },
          [
            item.type === "video"
              ? _c("video", { attrs: { src: item.url, controls: "" } })
              : _vm._e(),
            _vm._v(" "),
            item.type === "audio"
              ? _c("audio", { attrs: { src: item.url, controls: "" } })
              : _vm._e(),
            _vm._v(" "),
            item.type === "image"
              ? _c("img", { attrs: { src: item.url, alt: "" } })
              : _vm._e(),
            _vm._v(" "),
            _vm.hasRemove
              ? _c(
                  "div",
                  {
                    staticClass: "remove",
                    on: {
                      click: function($event) {
                        return _vm.clickedRemove(item, "main")
                      }
                    }
                  },
                  [
                    _c("font-awesome-icon", {
                      attrs: { icon: ["fa", "times"] }
                    })
                  ],
                  1
                )
              : _vm._e()
          ]
        )
      }),
      0
    ),
    _vm._v(" "),
    _vm.hasRemove && _vm.removedMessage.length
      ? _c("div", { staticClass: "message" }, [
          _vm._v("\n        " + _vm._s(_vm.removedMessage) + "\n    ")
        ])
      : _vm._e(),
    _vm._v(" "),
    _vm.hasRemove && _vm.removedFiles.length
      ? _c(
          "div",
          { staticClass: "removed" },
          _vm._l(_vm.removedFiles, function(item) {
            return _c(
              "div",
              {
                key: item.type + "." + item.id,
                staticClass: "single-file-preview"
              },
              [
                item.type === "video"
                  ? _c("video", { attrs: { src: item.url, controls: "" } })
                  : _vm._e(),
                _vm._v(" "),
                item.type === "audio"
                  ? _c("audio", { attrs: { src: item.url, controls: "" } })
                  : _vm._e(),
                _vm._v(" "),
                item.type === "image"
                  ? _c("img", { attrs: { src: item.url, alt: "" } })
                  : _vm._e(),
                _vm._v(" "),
                _c(
                  "div",
                  {
                    staticClass: "remove",
                    on: {
                      click: function($event) {
                        return _vm.clickedRemove(item, "removed")
                      }
                    }
                  },
                  [
                    _c("font-awesome-icon", {
                      attrs: { icon: ["fa", "times"] }
                    })
                  ],
                  1
                )
              ]
            )
          }),
          0
        )
      : _vm._e()
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=template&id=49938287&scoped=true&":
/*!************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=template&id=49938287&scoped=true& ***!
  \************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationButton.vue?vue&type=template&id=3da52dd1&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationButton.vue?vue&type=template&id=3da52dd1&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
    "button",
    {
      staticClass:
        "transition-colors text-gray-500 disabled:bg-gray-200 disabled:text-gray-400 p-2 \n        hover:text-whitesmoke border-b cursor-pointer hover:shadow-sm hover:bg-gray-800 rounded\n        disabled:cursor-not-allowed",
      attrs: { disabled: _vm.disabled }
    },
    [_vm._t("default")],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationComponent.vue?vue&type=template&id=34a84aa4&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationComponent.vue?vue&type=template&id=34a84aa4&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
    { staticClass: "flex-shrink-0 flex justify-around" },
    [
      _c(
        "navigation-button",
        {
          attrs: { disabled: _vm.isFirst },
          nativeOn: {
            click: function($event) {
              return _vm.clickedNavigator("previous")
            }
          }
        },
        [_vm._v("previous")]
      ),
      _vm._v(" "),
      _vm._l(_vm.buttons, function(button, index) {
        return _c(
          "navigation-button",
          {
            key: index,
            staticClass: "mx-2.5",
            nativeOn: {
              click: function($event) {
                return _vm.clickedNavigator(button)
              }
            }
          },
          [_vm._v(_vm._s(button))]
        )
      }),
      _vm._v(" "),
      _c(
        "navigation-button",
        {
          attrs: { disabled: _vm.isLast },
          nativeOn: {
            click: function($event) {
              return _vm.clickedNavigator("next")
            }
          }
        },
        [_vm._v("next")]
      )
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerBadge.vue?vue&type=template&id=142e45a2&scoped=true&":
/*!*********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerBadge.vue?vue&type=template&id=142e45a2&scoped=true& ***!
  \*********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
    { staticClass: "relative p-1" },
    [
      _c(
        "div",
        { staticClass: "bg-wheat rounded p-1" },
        [
          _vm.computedFiles.length
            ? _c("files-preview-backend", {
                attrs: { files: _vm.computedFiles }
              })
            : _vm._e(),
          _vm._v(" "),
          _c("div", { staticClass: "text-xs text-right text-gray-400" }, [
            _vm._v(_vm._s(_vm.answer.createdAt))
          ]),
          _vm._v(" "),
          _vm.answer.answer && _vm.answer.answer.length
            ? _c(
                "div",
                {
                  staticClass: "font-semibold text-gray-500",
                  attrs: { "data-testid": "answer" }
                },
                [_vm._v(_vm._s(_vm.answer.answer))]
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.computedPossibleAnswers.length
            ? _vm._l(_vm.computedPossibleAnswers, function(possibleAnswer) {
                return _c("possible-answer-badge", {
                  key: possibleAnswer.id,
                  staticClass: "p-0",
                  attrs: {
                    possibleAnswer: possibleAnswer,
                    drag: false,
                    answerType: _vm.answer.question.answerType,
                    marking: true
                  }
                })
              })
            : _vm._e()
        ],
        2
      ),
      _vm._v(" "),
      _vm.computedShowEditDeleteButtons
        ? _c(
            "div",
            { staticClass: "relative" },
            [
              _vm.computedScore.length
                ? _c(
                    "div",
                    { staticClass: "text-sm font-medium text-gray-500 p-1" },
                    [
                      _vm._v(
                        "\n            " +
                          _vm._s(_vm.computedScore) +
                          "\n        "
                      )
                    ]
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.computedRemark.length
                ? _c(
                    "div",
                    { staticClass: "text-xs font-medium text-gray-400 px-4" },
                    [
                      _vm._v(
                        "\n            " +
                          _vm._s(_vm.computedRemark) +
                          "\n        "
                      )
                    ]
                  )
                : _vm._e(),
              _vm._v(" "),
              _c("edit-delete-buttons", {
                staticClass: "text-sm flex",
                class: [
                  _vm.computedScore.length
                    ? "absolute right-0 top-0"
                    : "relative ml-auto"
                ],
                on: { click: _vm.clickedButton }
              })
            ],
            1
          )
        : _vm._e(),
      _vm._v(" "),
      _vm.answer.isMarker
        ? [
            !_vm.answer.mark
              ? _c(
                  "div",
                  { staticClass: "flex justify-end" },
                  [
                    _c(
                      "div",
                      {
                        staticClass:
                          "text-base p-2 cursor-pointer hover:text-green-500 z-30 mr-1",
                        class: [
                          _vm.computedIsCorrect
                            ? "text-green-500"
                            : "text-gray-500"
                        ],
                        attrs: { "data-testid": "correct" },
                        on: {
                          click: function($event) {
                            return _vm.clickedButton("correct")
                          }
                        }
                      },
                      [
                        _c("font-awesome-icon", {
                          attrs: { icon: ["fa", "check"] }
                        })
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c("partial-mark", {
                      staticClass:
                        "text-base p-2 cursor-pointer hover:text-green-300 z-30 mr-1",
                      class: [
                        _vm.computedIsPartial
                          ? "text-green-300"
                          : "text-gray-500"
                      ],
                      nativeOn: {
                        click: function($event) {
                          return _vm.clickedButton("partial")
                        }
                      }
                    }),
                    _vm._v(" "),
                    _c(
                      "div",
                      {
                        staticClass:
                          "text-base p-2 cursor-pointer hover:text-red-500 z-30",
                        class: [
                          _vm.computedIsWrong ? "text-red-500" : "text-gray-500"
                        ],
                        attrs: { "data-testid": "wrong" },
                        on: {
                          click: function($event) {
                            return _vm.clickedButton("wrong")
                          }
                        }
                      },
                      [
                        _c("font-awesome-icon", {
                          attrs: { icon: ["fa", "times"] }
                        })
                      ],
                      1
                    )
                  ],
                  1
                )
              : _vm._e()
          ]
        : _vm._e()
    ],
    2
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
      staticClass: "assessment-section-answering-badge",
      class: {
        "flex justify-center items-center h-full w-full": !_vm.computedTimingShow
      }
    },
    [
      _vm.computedTimingShow
        ? [
            _vm.timingItemTimeLeft
              ? _c(
                  "div",
                  {
                    staticClass:
                      "flex p-0.5 w-content justify-end pr-1 text-sm",
                    class: [
                      _vm.timingItemHasFewTimeLeft
                        ? "bg-red-700 text-gray-200"
                        : "text-gray-500"
                    ]
                  },
                  [_c("div", [_vm._v(_vm._s(_vm.timingItemTimeLeft))])]
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.assessmentSection
              ? [
                  _c("assessment-section-information-badge", {
                    attrs: { assessmentSection: _vm.assessmentSection }
                  }),
                  _vm._v(" "),
                  _c(
                    "div",
                    {
                      staticClass:
                        "h-full max-h-3/4 flex-shrink mb-2 overflow-y-auto p-2"
                    },
                    _vm._l(_vm.assessmentSection.questions, function(
                      question,
                      index
                    ) {
                      return _c("question-answering-badge", {
                        key: index,
                        attrs: {
                          question: question,
                          answer: _vm.getProvidedAnswer(question),
                          questionAnswer: _vm.getQuestionAnswer(question)
                        },
                        on: { answered: _vm.answered }
                      })
                    }),
                    1
                  )
                ]
              : _vm._e(),
            _vm._v(" "),
            !_vm.assessmentSection
              ? _c(
                  "div",
                  { staticClass: "text-gray-500 font-semibold text-sm px-2" },
                  [
                    _vm._v(
                      "\n            " +
                        _vm._s("sorry 😕, no section yet") +
                        "\n        "
                    )
                  ]
                )
              : _vm._e()
          ]
        : _vm._e(),
      _vm._v(" "),
      _vm.timingItemLocked
        ? _c(
            "div",
            { staticClass: "text-gray-500 font-semibold text-sm px-2" },
            [
              _vm._v(
                "\n        " +
                  _vm._s(
                    "sorry 😕, you have no time left for answering this assessment section with name: " +
                      _vm.assessmentSection.name
                  ) +
                  "\n    "
              )
            ]
          )
        : _vm._e(),
      _vm._v(" "),
      _vm.timingItemWait
        ? _c(
            "div",
            {
              staticClass:
                "p-2 rounded-sm text-gray-500 font-semibold text-sm bg-yellow-400"
            },
            [_vm._v("\n        ✋ wait for a while...\n    ")]
          )
        : _vm._e(),
      _vm._v(" "),
      _c("pop-up", {
        attrs: {
          show: _vm.showPopUp,
          responses: ["continue", "cancel"],
          default: "continue",
          message:
            "this assessment section has a duration. once you click continue, a timer will start and you will have to finish before the timer completes"
        },
        on: {
          clickedResponse: _vm.clickedPopupResponse,
          closePopUp: _vm.closePopUp
        }
      })
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=template&id=7b88a064&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=template&id=7b88a064&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _vm.assessmentSection
    ? _c("div", [
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
                  staticClass:
                    "overflow-ellipsis text-center text-gray-500 text-sm"
                },
                [_vm._v(_vm._s(_vm.assessmentSection.instruction))]
              )
            : _vm._e(),
          _vm._v(" "),
          _c("div", { staticClass: "text-xs text-right text-gray-400" }, [
            _vm._v(
              _vm._s(_vm.assessmentSection.questions.length + " questions")
            )
          ]),
          _vm._v(" "),
          _vm.assessmentSection.random
            ? _c("div", { staticClass: "text-gray-400 text-right text-xs" }, [
                _vm._v("the questions where selected randomly")
              ])
            : _vm._e()
        ])
      ])
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=template&id=bbeb6d04&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/PossibleAnswerBadge.vue?vue&type=template&id=bbeb6d04&scoped=true& ***!
  \*****************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
                        {
                          staticClass: "option",
                          class: [_vm.nodeClassHas("p-") ? "" : "p-2.5 pl-0"],
                          on: { click: _vm.clickedUpdateCorrect }
                        },
                        [
                          !_vm.removed && !_vm.marking
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
                          _vm.computedCanUpdateCorrect
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
                        {
                          staticClass: "true-false",
                          class: [_vm.nodeClassHas("p-") ? "" : "p-2.5 pl-0"],
                          on: { click: _vm.clickedUpdateCorrect }
                        },
                        [
                          !_vm.marking
                            ? _c("div", { staticClass: "list-identifier" })
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
                          _vm.computedCanUpdateCorrect
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
                    ? _c("div", { staticClass: "arrange p-2.5" }, [
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
            _c("div", { staticClass: "mb-2 text-gray-600 font-semibold" }, [
              _vm._v(_vm._s(_vm.question.body))
            ]),
            _vm._v(" "),
            _vm.question.hint && _vm.question.length
              ? _c("main-checkbox", {
                  attrs: { label: "show hint" },
                  model: {
                    value: _vm.showHint,
                    callback: function($$v) {
                      _vm.showHint = $$v
                    },
                    expression: "showHint"
                  }
                })
              : _vm._e(),
            _vm._v(" "),
            _vm.showHint
              ? _c(
                  "div",
                  { staticClass: "text-sm text-gray-500 w-11/12 mt-1 mx-auto" },
                  [_vm._v(_vm._s(_vm.question.hint))]
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.show
              ? _c("answer-form", {
                  attrs: {
                    answerType: _vm.question.answerType,
                    possibleAnswers: _vm.question.possibleAnswers,
                    providedAnswer: _vm.answer,
                    currentAnswer: _vm.questionAnswer
                  },
                  on: { answered: _vm.answered }
                })
              : _c("answer-badge", {
                  attrs: {
                    answer: _vm.questionAnswer,
                    hasEditDeleteButtons: true
                  },
                  on: { clickedButton: _vm.clickedButton }
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
      _vm.computedAnswerType === "number"
        ? _c("number-input", {
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
              _vm._l(_vm.answerPossibleAnswers, function(
                possibleAnswer,
                possibleAnswerIndex
              ) {
                return _c("possible-answer-badge", {
                  key: possibleAnswerIndex,
                  attrs: {
                    possibleAnswerIndex: possibleAnswerIndex,
                    answering: true,
                    answerType: _vm.answerType,
                    drag: _vm.computedDrag,
                    possibleAnswer: possibleAnswer,
                    possibleAnswersLength: _vm.possibleAnswers.length,
                    correctPossibleAnswers: _vm.computedCorrectPossibleAnswers
                  },
                  on: {
                    movePossibleAnswer: _vm.movePossibleAnswer,
                    possibleAnswerIsCorrect: _vm.possibleAnswerIsCorrect
                  }
                })
              }),
              1
            )
          ])
        : _vm._e(),
      _vm._v(" "),
      _vm.currentAnswer
        ? _c("div", { staticClass: "w-full p-2 flex justify-end" }, [
            _vm.computedHasData
              ? _c(
                  "div",
                  {
                    staticClass:
                      "text-base p-2 cursor-pointer hover:text-green-500 z-30 mr-1 text-gray-500",
                    on: {
                      click: function($event) {
                        return _vm.clickedButton("update")
                      }
                    }
                  },
                  [_vm._v("\n            update\n        ")]
                )
              : _vm._e(),
            _vm._v(" "),
            _c(
              "div",
              {
                staticClass:
                  "text-base p-2 cursor-pointer hover:text-green-500 z-30 text-gray-500",
                on: {
                  click: function($event) {
                    return _vm.clickedButton("cancel")
                  }
                }
              },
              [_vm._v("\n            cancel\n        ")]
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
      staticClass:
        "assessment-section-answering-form pb-2 flex flex-col justify-center"
    },
    [
      _c("assessment-section-answering-badge", {
        staticClass: "h-full flex-shrink",
        attrs: {
          assessmentSection: _vm.currentAssessmentSection,
          answers: _vm.answers,
          sectionAnswers: _vm.computedWorkAnswers,
          computedAccount: _vm.computedAccount,
          assessmentId: _vm.assessment.id
        },
        on: {
          answered: _vm.answered,
          initiated: _vm.initiatedAssessmentSection,
          clickedResponse: _vm.clickedPopupResponse,
          noTimeLeft: _vm.noTimeLeft,
          fewMinutesMore: _vm.fewMinutesMore
        }
      }),
      _vm._v(" "),
      _c("navigation-component", {
        attrs: {
          isFirst: _vm.computedIsFirstSection,
          isLast: _vm.computedIsLastSection,
          buttons: _vm.computedPossiblyDone ? ["done"] : []
        },
        on: { clickedNavigator: _vm.clickedSectionNavigator }
      })
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/PartialMark.vue?vue&type=template&id=9588cf38&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/PartialMark.vue?vue&type=template&id=9588cf38&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
      "svg",
      {
        staticClass: "svg-inline--fa",
        attrs: {
          xmlns: "http://www.w3.org/2000/svg",
          viewBox: "0 0 261.4 177.91",
          fill: "currentColor",
          role: "image"
        }
      },
      [
        _c("g", { attrs: { id: "Layer_2", "data-name": "Layer 2" } }, [
          _c("g", { attrs: { id: "Layer_1-2", "data-name": "Layer 1" } }, [
            _c("polyline", {
              staticClass: "cls-1",
              attrs: { points: "10.07 84.72 76.5 159.12 252.69 10.31" }
            }),
            _vm._v(" "),
            _c("line", {
              staticClass: "cls-1",
              attrs: { x1: "152.25", y1: "166.86", x2: "246.28", y2: "84.72" }
            })
          ])
        ])
      ]
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);