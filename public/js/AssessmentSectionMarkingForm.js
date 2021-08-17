(self["webpackChunk"] = self["webpackChunk"] || []).push([["AssessmentSectionMarkingForm"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationButtons.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationButtons.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************/
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    isFirst: {
      type: Boolean,
      "default": false
    },
    isLast: {
      type: Boolean,
      "default": false
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

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _FilesPreviewBackend_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../FilesPreviewBackend.vue */ "./resources/js/components/FilesPreviewBackend.vue");
/* harmony import */ var _mixins_Files_mixin__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../mixins/Files.mixin */ "./resources/js/mixins/Files.mixin.js");
/* harmony import */ var _PossibleAnswerBadge_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./PossibleAnswerBadge.vue */ "./resources/js/components/dashboard/PossibleAnswerBadge.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    PossibleAnswerBadge: _PossibleAnswerBadge_vue__WEBPACK_IMPORTED_MODULE_2__.default
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
    }
  },
  methods: {
    clickedMarkButton: function clickedMarkButton(type) {
      this.$emit('clickedMarkButton', {
        type: type,
        answer: this.answer
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _AnswerMark_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../AnswerMark.vue */ "./resources/js/components/AnswerMark.vue");
/* harmony import */ var _AnswerBadge_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AnswerBadge.vue */ "./resources/js/components/dashboard/AnswerBadge.vue");
/* harmony import */ var _QuestionBadge_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./QuestionBadge.vue */ "./resources/js/components/dashboard/QuestionBadge.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    AnswerMark: _AnswerMark_vue__WEBPACK_IMPORTED_MODULE_0__.default,
    QuestionBadge: _QuestionBadge_vue__WEBPACK_IMPORTED_MODULE_2__.default,
    AnswerBadge: _AnswerBadge_vue__WEBPACK_IMPORTED_MODULE_1__.default
  },
  props: {
    answer: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    providedMark: {
      type: Object,
      "default": function _default() {
        return null;
      }
    }
  },
  data: function data() {
    return {
      mark: {
        score: null,
        remark: '',
        answerId: null
      },
      showMarkForm: false
    };
  },
  watch: {
    providedMark: {
      immediate: true,
      handler: function handler(newValue) {
        if (!newValue) {
          return;
        }

        this.initiateMark();
      },
      deep: true
    }
  },
  computed: {
    computedScoreOver: function computedScoreOver() {
      var _String;

      return (_String = String(this.answer.question.scoreOver)) !== null && _String !== void 0 ? _String : '100';
    }
  },
  methods: {
    getScore: function getScore(data) {
      this.updateMark({
        score: data.score,
        remark: data.remark
      });
      this.showMarkForm = false;
      this.emitMark();
    },
    updateMark: function updateMark(_ref) {
      var score = _ref.score,
          remark = _ref.remark;
      this.mark.score = score;
      this.mark.remark = remark !== null && remark !== void 0 ? remark : '';
      this.mark.answerId = this.answer.id;
    },
    emitMark: function emitMark() {
      this.$emit('marked', this.mark);
    },
    initiateMark: function initiateMark() {
      if (!this.providedMark) {
        this.clearMark();
        return;
      }

      this.updateMark({
        score: this.providedMark.score,
        remark: this.providedMark.remark
      });
    },
    clearMark: function clearMark() {
      this.mark.score = null;
      this.mark.remark = '';
      this.mark.answerId = null;
    },
    clickedMarkButton: function clickedMarkButton(data) {
      var score = 0;

      if (data.type === 'correct') {
        score = this.answer.question.scoreOver;
      }

      this.updateMark({
        score: score
      });
      this.showMarkForm = true;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************************************************/
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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _FilesPreviewBackend_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../FilesPreviewBackend.vue */ "./resources/js/components/FilesPreviewBackend.vue");
/* harmony import */ var _AnswerMarkingBadge_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AnswerMarkingBadge.vue */ "./resources/js/components/dashboard/AnswerMarkingBadge.vue");
/* harmony import */ var _AssessmentSectionInformationBadge_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./AssessmentSectionInformationBadge.vue */ "./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue");
//
//
//
//
//
//
//
//
//
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
    AnswerMarkingBadge: _AnswerMarkingBadge_vue__WEBPACK_IMPORTED_MODULE_1__.default,
    FilesPreviewBackend: _FilesPreviewBackend_vue__WEBPACK_IMPORTED_MODULE_0__.default,
    AssessmentSectionInformationBadge: _AssessmentSectionInformationBadge_vue__WEBPACK_IMPORTED_MODULE_2__.default
  },
  props: {
    answers: {
      type: Array,
      "default": function _default() {
        return null;
      }
    },
    marks: {
      type: Array,
      "default": function _default() {
        return null;
      }
    },
    assessmentSection: {
      type: Object,
      "default": function _default() {
        return null;
      }
    }
  },
  computed: {},
  methods: {
    marked: function marked(data) {
      data.assessmentSectionId = this.assessmentSection.id;
      this.$emit('marked', data);
    },
    getSpecificMark: function getSpecificMark(answer) {
      return this.marks.find(function (mark) {
        return mark.answerId == answer.id;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _FilePreview__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../FilePreview */ "./resources/js/components/FilePreview.vue");
/* harmony import */ var _PossibleAnswerBadge__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./PossibleAnswerBadge */ "./resources/js/components/dashboard/PossibleAnswerBadge.vue");
/* harmony import */ var _mixins_Files_mixin__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../mixins/Files.mixin */ "./resources/js/mixins/Files.mixin.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    PossibleAnswerBadge: _PossibleAnswerBadge__WEBPACK_IMPORTED_MODULE_1__.default,
    FilePreview: _FilePreview__WEBPACK_IMPORTED_MODULE_0__.default
  },
  props: {
    question: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    drag: {
      type: Boolean,
      "default": false
    },
    backgroundClass: {
      type: String,
      "default": null
    },
    noFiles: {
      type: Boolean,
      "default": false
    },
    close: {
      type: Boolean,
      "default": true
    },
    removed: {
      type: Boolean,
      "default": false
    }
  },
  mixins: [_mixins_Files_mixin__WEBPACK_IMPORTED_MODULE_2__.default],
  computed: {
    computedItemable: function computedItemable() {
      return this.question;
    },
    computedClasses: function computedClasses() {
      return this.$vnode.data.staticClass ? this.$vnode.data.staticClass : '';
    }
  },
  methods: {
    clickedDrag: function clickedDrag() {
      this.$emit('arrangeQuestions');
    },
    clickedClose: function clickedClose() {
      if (this.removed) {
        this.$emit('undoQuestionRemoval', this.question);
        return;
      }

      this.$emit('removeQuestion', this.question);
    },
    dbclickedQuestion: function dbclickedQuestion() {
      this.$emit('editQuestion', this.question);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _dashboard_AssessmentSectionMarkingBadge_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../dashboard/AssessmentSectionMarkingBadge.vue */ "./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue");
/* harmony import */ var _NavigationButtons_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../NavigationButtons.vue */ "./resources/js/components/NavigationButtons.vue");
//
//
//
//
//
//
//
//
//
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
    AssessmentSectionMarkingBadge: _dashboard_AssessmentSectionMarkingBadge_vue__WEBPACK_IMPORTED_MODULE_0__.default,
    NavigationButtons: _NavigationButtons_vue__WEBPACK_IMPORTED_MODULE_1__.default
  },
  props: {
    marks: {
      type: Array,
      "default": function _default() {
        return null;
      }
    },
    work: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    assessment: {
      type: Object,
      "default": function _default() {
        return null;
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
  watch: {
    work: {
      immediate: true,
      handler: function handler(newValue) {
        this.initiate();
      }
    }
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
    computedAnswers: function computedAnswers() {
      var _this$currentAssessme3,
          _this = this;

      if (!this.work || !((_this$currentAssessme3 = this.currentAssessmentSection) !== null && _this$currentAssessme3 !== void 0 && _this$currentAssessme3.id)) {
        return null;
      }

      return this.work.answers.filter(function (answer) {
        return answer.assessmentSectionId == _this.currentAssessmentSection.id;
      });
    },
    computedMarks: function computedMarks() {
      var _this2 = this;

      return this.marks.filter(function (mark) {
        return mark.assessmentSectionId == _this2.currentAssessmentSection.id;
      });
    }
  },
  methods: {
    initiate: function initiate() {
      this.firstAssessmentSectionId = this.assessment.assessmentSections[0].id;
      this.lastAssessmentSectionId = this.assessment.assessmentSections[this.assessment.assessmentSections.length - 1].id;
      this.clickedSectionNavigator('next');
    },
    clickedSectionNavigator: function clickedSectionNavigator(text) {
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
    marked: function marked(data) {
      this.$emit('marked', data);
    }
  }
});

/***/ }),

/***/ "./resources/js/mixins/Files.mixin.js":
/*!********************************************!*\
  !*** ./resources/js/mixins/Files.mixin.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, exports, __webpack_require__) => {

// Imports
var ___CSS_LOADER_API_IMPORT___ = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
exports = ___CSS_LOADER_API_IMPORT___(false);
// Module
exports.push([module.id, ".small-msg[data-v-daab0f30] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.files-preview-backend-wrapper .message[data-v-daab0f30] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n}\n.files-preview-backend-wrapper .main[data-v-daab0f30],\n.files-preview-backend-wrapper .removed[data-v-daab0f30] {\n  width: 90%;\n  max-width: 400px;\n  margin: 10px auto;\n  max-height: 400px;\n  display: flex;\n  flex-wrap: nowrap;\n  overflow-y: hidden;\n  overflow-x: auto;\n  padding: 10px;\n  align-items: center;\n}\n.files-preview-backend-wrapper .main .single-file-preview[data-v-daab0f30],\n.files-preview-backend-wrapper .removed .single-file-preview[data-v-daab0f30] {\n  min-width: 100%;\n  max-width: 100%;\n  position: relative;\n  padding: 5px;\n  padding-bottom: 0;\n  background: mintcream;\n  margin-right: 10px;\n}\n.files-preview-backend-wrapper .main .single-file-preview video[data-v-daab0f30],\n.files-preview-backend-wrapper .main .single-file-preview img[data-v-daab0f30],\n.files-preview-backend-wrapper .removed .single-file-preview video[data-v-daab0f30],\n.files-preview-backend-wrapper .removed .single-file-preview img[data-v-daab0f30] {\n  width: 100%;\n  height: 100%;\n  -o-object-fit: contain;\n     object-fit: contain;\n  -o-object-position: center;\n     object-position: center;\n}\n.files-preview-backend-wrapper .main .single-file-preview audio[data-v-daab0f30],\n.files-preview-backend-wrapper .removed .single-file-preview audio[data-v-daab0f30] {\n  width: 100%;\n}\n.files-preview-backend-wrapper .main .remove[data-v-daab0f30],\n.files-preview-backend-wrapper .removed .remove[data-v-daab0f30] {\n  position: absolute;\n  z-index: 1;\n  top: 10px;\n  right: 10px;\n  padding: 5px;\n  font-size: 16px;\n  color: red;\n  cursor: pointer;\n}\n.files-preview-backend-wrapper .removed[data-v-daab0f30] {\n  background: red;\n}", ""]);
// Exports
module.exports = exports;


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true&":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, exports, __webpack_require__) => {

// Imports
var ___CSS_LOADER_API_IMPORT___ = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
exports = ___CSS_LOADER_API_IMPORT___(false);
// Module
exports.push([module.id, ".small-msg[data-v-3697b187] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.question-badge-wrapper[data-v-3697b187] {\n  min-width: 100%;\n  display: flex;\n  width: 100%;\n  align-items: center;\n  justify-content: center;\n}\n.question-badge-wrapper .other[data-v-3697b187] {\n  position: absolute;\n  right: 10px;\n  top: 5px;\n}\n.question-badge-wrapper .other .drag[data-v-3697b187] {\n  cursor: -webkit-grab;\n  cursor: grab;\n  font-size: 20px;\n  color: gray;\n}\n.question-badge-wrapper .other .close[data-v-3697b187] {\n  font-size: 30px;\n  color: gray;\n  padding: 5px;\n  cursor: pointer;\n}\n.question-badge-wrapper .other .close[data-v-3697b187]:hover {\n  color: red;\n}\n.question-badge-wrapper .main[data-v-3697b187] {\n  padding: 10px;\n  border-radius: 10px;\n  cursor: pointer;\n  width: 100%;\n}\n.question-badge-wrapper .main .body[data-v-3697b187] {\n  font-size: 14px;\n  color: black;\n}\n.question-badge-wrapper .main .file[data-v-3697b187] {\n  margin: 0 0 10px;\n}\n.question-badge-wrapper .main .hint[data-v-3697b187] {\n  font-size: 12px;\n  color: gray;\n  width: 100%;\n  text-align: center;\n  margin: 5px;\n}\n.question-badge-wrapper .main .score[data-v-3697b187] {\n  font-size: 12px;\n  color: gray;\n  width: 100%;\n  text-align: end;\n}", ""]);
// Exports
module.exports = exports;


/***/ }),

/***/ "./resources/js/components/FilesPreviewBackend.vue":
/*!*********************************************************!*\
  !*** ./resources/js/components/FilesPreviewBackend.vue ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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

/***/ "./resources/js/components/NavigationButtons.vue":
/*!*******************************************************!*\
  !*** ./resources/js/components/NavigationButtons.vue ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _NavigationButtons_vue_vue_type_template_id_7aac2d72_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NavigationButtons.vue?vue&type=template&id=7aac2d72&scoped=true& */ "./resources/js/components/NavigationButtons.vue?vue&type=template&id=7aac2d72&scoped=true&");
/* harmony import */ var _NavigationButtons_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./NavigationButtons.vue?vue&type=script&lang=js& */ "./resources/js/components/NavigationButtons.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _NavigationButtons_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _NavigationButtons_vue_vue_type_template_id_7aac2d72_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _NavigationButtons_vue_vue_type_template_id_7aac2d72_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "7aac2d72",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/NavigationButtons.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/dashboard/AnswerBadge.vue":
/*!***********************************************************!*\
  !*** ./resources/js/components/dashboard/AnswerBadge.vue ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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

/***/ "./resources/js/components/dashboard/AnswerMarkingBadge.vue":
/*!******************************************************************!*\
  !*** ./resources/js/components/dashboard/AnswerMarkingBadge.vue ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _AnswerMarkingBadge_vue_vue_type_template_id_e316f95c_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AnswerMarkingBadge.vue?vue&type=template&id=e316f95c&scoped=true& */ "./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=template&id=e316f95c&scoped=true&");
/* harmony import */ var _AnswerMarkingBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AnswerMarkingBadge.vue?vue&type=script&lang=js& */ "./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _AnswerMarkingBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _AnswerMarkingBadge_vue_vue_type_template_id_e316f95c_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _AnswerMarkingBadge_vue_vue_type_template_id_e316f95c_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "e316f95c",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/dashboard/AnswerMarkingBadge.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue":
/*!*********************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue ***!
  \*********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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

/***/ "./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _AssessmentSectionMarkingBadge_vue_vue_type_template_id_113c683b_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AssessmentSectionMarkingBadge.vue?vue&type=template&id=113c683b&scoped=true& */ "./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=template&id=113c683b&scoped=true&");
/* harmony import */ var _AssessmentSectionMarkingBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AssessmentSectionMarkingBadge.vue?vue&type=script&lang=js& */ "./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _AssessmentSectionMarkingBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _AssessmentSectionMarkingBadge_vue_vue_type_template_id_113c683b_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _AssessmentSectionMarkingBadge_vue_vue_type_template_id_113c683b_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "113c683b",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/dashboard/QuestionBadge.vue":
/*!*************************************************************!*\
  !*** ./resources/js/components/dashboard/QuestionBadge.vue ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _QuestionBadge_vue_vue_type_template_id_3697b187_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./QuestionBadge.vue?vue&type=template&id=3697b187&scoped=true& */ "./resources/js/components/dashboard/QuestionBadge.vue?vue&type=template&id=3697b187&scoped=true&");
/* harmony import */ var _QuestionBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./QuestionBadge.vue?vue&type=script&lang=js& */ "./resources/js/components/dashboard/QuestionBadge.vue?vue&type=script&lang=js&");
/* harmony import */ var _QuestionBadge_vue_vue_type_style_index_0_id_3697b187_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true& */ "./resources/js/components/dashboard/QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__.default)(
  _QuestionBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _QuestionBadge_vue_vue_type_template_id_3697b187_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _QuestionBadge_vue_vue_type_template_id_3697b187_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "3697b187",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/dashboard/QuestionBadge.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/forms/AssessmentSectionMarkingForm.vue":
/*!************************************************************************!*\
  !*** ./resources/js/components/forms/AssessmentSectionMarkingForm.vue ***!
  \************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _AssessmentSectionMarkingForm_vue_vue_type_template_id_8e10cdbe_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AssessmentSectionMarkingForm.vue?vue&type=template&id=8e10cdbe&scoped=true& */ "./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=template&id=8e10cdbe&scoped=true&");
/* harmony import */ var _AssessmentSectionMarkingForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AssessmentSectionMarkingForm.vue?vue&type=script&lang=js& */ "./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _AssessmentSectionMarkingForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _AssessmentSectionMarkingForm_vue_vue_type_template_id_8e10cdbe_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _AssessmentSectionMarkingForm_vue_vue_type_template_id_8e10cdbe_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "8e10cdbe",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/forms/AssessmentSectionMarkingForm.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/FilesPreviewBackend.vue?vue&type=script&lang=js&":
/*!**********************************************************************************!*\
  !*** ./resources/js/components/FilesPreviewBackend.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./FilesPreviewBackend.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/NavigationButtons.vue?vue&type=script&lang=js&":
/*!********************************************************************************!*\
  !*** ./resources/js/components/NavigationButtons.vue?vue&type=script&lang=js& ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NavigationButtons_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./NavigationButtons.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationButtons.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NavigationButtons_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/dashboard/AnswerBadge.vue?vue&type=script&lang=js&":
/*!************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AnswerBadge.vue?vue&type=script&lang=js& ***!
  \************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AnswerBadge.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerBadge.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerMarkingBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AnswerMarkingBadge.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerMarkingBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionInformationBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionInformationBadge.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionInformationBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionMarkingBadge.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/dashboard/QuestionBadge.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/js/components/dashboard/QuestionBadge.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./QuestionBadge.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionMarkingForm.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/FilesPreviewBackend.vue?vue&type=template&id=daab0f30&scoped=true&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/components/FilesPreviewBackend.vue?vue&type=template&id=daab0f30&scoped=true& ***!
  \****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_template_id_daab0f30_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_template_id_daab0f30_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_template_id_daab0f30_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./FilesPreviewBackend.vue?vue&type=template&id=daab0f30&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=template&id=daab0f30&scoped=true&");


/***/ }),

/***/ "./resources/js/components/NavigationButtons.vue?vue&type=template&id=7aac2d72&scoped=true&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/components/NavigationButtons.vue?vue&type=template&id=7aac2d72&scoped=true& ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NavigationButtons_vue_vue_type_template_id_7aac2d72_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NavigationButtons_vue_vue_type_template_id_7aac2d72_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NavigationButtons_vue_vue_type_template_id_7aac2d72_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./NavigationButtons.vue?vue&type=template&id=7aac2d72&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationButtons.vue?vue&type=template&id=7aac2d72&scoped=true&");


/***/ }),

/***/ "./resources/js/components/dashboard/AnswerBadge.vue?vue&type=template&id=142e45a2&scoped=true&":
/*!******************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AnswerBadge.vue?vue&type=template&id=142e45a2&scoped=true& ***!
  \******************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerBadge_vue_vue_type_template_id_142e45a2_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerBadge_vue_vue_type_template_id_142e45a2_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerBadge_vue_vue_type_template_id_142e45a2_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AnswerBadge.vue?vue&type=template&id=142e45a2&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerBadge.vue?vue&type=template&id=142e45a2&scoped=true&");


/***/ }),

/***/ "./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=template&id=e316f95c&scoped=true&":
/*!*************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=template&id=e316f95c&scoped=true& ***!
  \*************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerMarkingBadge_vue_vue_type_template_id_e316f95c_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerMarkingBadge_vue_vue_type_template_id_e316f95c_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerMarkingBadge_vue_vue_type_template_id_e316f95c_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AnswerMarkingBadge.vue?vue&type=template&id=e316f95c&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=template&id=e316f95c&scoped=true&");


/***/ }),

/***/ "./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=template&id=7b88a064&scoped=true&":
/*!****************************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=template&id=7b88a064&scoped=true& ***!
  \****************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionInformationBadge_vue_vue_type_template_id_7b88a064_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionInformationBadge_vue_vue_type_template_id_7b88a064_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionInformationBadge_vue_vue_type_template_id_7b88a064_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionInformationBadge.vue?vue&type=template&id=7b88a064&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=template&id=7b88a064&scoped=true&");


/***/ }),

/***/ "./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=template&id=113c683b&scoped=true&":
/*!************************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=template&id=113c683b&scoped=true& ***!
  \************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingBadge_vue_vue_type_template_id_113c683b_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingBadge_vue_vue_type_template_id_113c683b_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingBadge_vue_vue_type_template_id_113c683b_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionMarkingBadge.vue?vue&type=template&id=113c683b&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=template&id=113c683b&scoped=true&");


/***/ }),

/***/ "./resources/js/components/dashboard/QuestionBadge.vue?vue&type=template&id=3697b187&scoped=true&":
/*!********************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/QuestionBadge.vue?vue&type=template&id=3697b187&scoped=true& ***!
  \********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_template_id_3697b187_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_template_id_3697b187_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_template_id_3697b187_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./QuestionBadge.vue?vue&type=template&id=3697b187&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=template&id=3697b187&scoped=true&");


/***/ }),

/***/ "./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=template&id=8e10cdbe&scoped=true&":
/*!*******************************************************************************************************************!*\
  !*** ./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=template&id=8e10cdbe&scoped=true& ***!
  \*******************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingForm_vue_vue_type_template_id_8e10cdbe_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingForm_vue_vue_type_template_id_8e10cdbe_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingForm_vue_vue_type_template_id_8e10cdbe_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionMarkingForm.vue?vue&type=template&id=8e10cdbe&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=template&id=8e10cdbe&scoped=true&");


/***/ }),

/***/ "./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true&":
/*!*******************************************************************************************************************!*\
  !*** ./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true& ***!
  \*******************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_style_index_0_id_daab0f30_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-style-loader/index.js!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true& */ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_style_index_0_id_daab0f30_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_style_index_0_id_daab0f30_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ var __WEBPACK_REEXPORT_OBJECT__ = {};
/* harmony reexport (unknown) */ for(const __WEBPACK_IMPORT_KEY__ in _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_style_index_0_id_daab0f30_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== "default") __WEBPACK_REEXPORT_OBJECT__[__WEBPACK_IMPORT_KEY__] = () => _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_style_index_0_id_daab0f30_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__[__WEBPACK_IMPORT_KEY__]
/* harmony reexport (unknown) */ __webpack_require__.d(__webpack_exports__, __WEBPACK_REEXPORT_OBJECT__);


/***/ }),

/***/ "./resources/js/components/dashboard/QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true&":
/*!***********************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true& ***!
  \***********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_style_index_0_id_3697b187_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-style-loader/index.js!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true& */ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_style_index_0_id_3697b187_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_style_index_0_id_3697b187_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ var __WEBPACK_REEXPORT_OBJECT__ = {};
/* harmony reexport (unknown) */ for(const __WEBPACK_IMPORT_KEY__ in _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_style_index_0_id_3697b187_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== "default") __WEBPACK_REEXPORT_OBJECT__[__WEBPACK_IMPORT_KEY__] = () => _node_modules_vue_style_loader_index_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_style_index_0_id_3697b187_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__[__WEBPACK_IMPORT_KEY__]
/* harmony reexport (unknown) */ __webpack_require__.d(__webpack_exports__, __WEBPACK_REEXPORT_OBJECT__);


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=template&id=daab0f30&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=template&id=daab0f30&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationButtons.vue?vue&type=template&id=7aac2d72&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/NavigationButtons.vue?vue&type=template&id=7aac2d72&scoped=true& ***!
  \*****************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "flex-shrink-0 flex justify-around" }, [
    _c(
      "button",
      {
        staticClass:
          "transition-colors text-gray-500 disabled:bg-gray-200 disabled:text-gray-400 p-2 \n            hover:text-whitesmoke border-b cursor-pointer hover:shadow-sm hover:bg-gray-800 rounded",
        attrs: { disabled: _vm.isFirst },
        on: {
          click: function($event) {
            return _vm.clickedNavigator("previous")
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
          "transition-colors text-gray-500 disabled:bg-gray-200 disabled:text-gray-400 p-2 \n            hover:text-whitesmoke border-b cursor-pointer hover:shadow-sm hover:bg-gray-800 rounded",
        attrs: { disabled: _vm.isLast },
        on: {
          click: function($event) {
            return _vm.clickedNavigator("next")
          }
        }
      },
      [_vm._v("next")]
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerBadge.vue?vue&type=template&id=142e45a2&scoped=true&":
/*!*********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerBadge.vue?vue&type=template&id=142e45a2&scoped=true& ***!
  \*********************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "relative p-1" }, [
    _c(
      "div",
      { staticClass: "bg-wheat rounded p-1" },
      [
        _vm.computedFiles.length
          ? _c("files-preview-backend", { attrs: { files: _vm.computedFiles } })
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
    _vm.answer.isMarker
      ? _c("div", { staticClass: "flex justify-end" }, [
          _c(
            "div",
            {
              staticClass:
                "text-base p-2 cursor-pointer hover:text-green-500 z-30 mr-1",
              class: [
                _vm.computedIsCorrect ? "text-green-500" : "text-gray-500"
              ],
              attrs: { "data-testid": "correct" },
              on: {
                click: function($event) {
                  return _vm.clickedMarkButton("correct")
                }
              }
            },
            [_c("font-awesome-icon", { attrs: { icon: ["fa", "check"] } })],
            1
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass:
                "text-base p-2 cursor-pointer hover:text-red-500 z-30",
              class: [_vm.computedIsWrong ? "text-red-500" : "text-gray-500"],
              attrs: { "data-testid": "wrong" },
              on: {
                click: function($event) {
                  return _vm.clickedMarkButton("wrong")
                }
              }
            },
            [_c("font-awesome-icon", { attrs: { icon: ["fa", "times"] } })],
            1
          )
        ])
      : _vm._e()
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=template&id=e316f95c&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=template&id=e316f95c&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************************/
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
  return _vm.answer
    ? _c(
        "div",
        { staticClass: "relative bg-white rounded mb-1" },
        [
          _c("question-badge", {
            staticClass: "mx-0 mb-1",
            attrs: {
              question: _vm.answer.question,
              close: false,
              backgroundClass: "bg-none"
            }
          }),
          _vm._v(" "),
          _c("answer-badge", {
            attrs: { answer: _vm.answer, mark: _vm.mark },
            on: { clickedMarkButton: _vm.clickedMarkButton }
          }),
          _vm._v(" "),
          _c("answer-mark", {
            staticClass: "absolute right-0 top-0",
            attrs: {
              show: _vm.showMarkForm,
              inputMax: _vm.answer.question.scoreOver,
              inputMin: 0,
              value: _vm.mark,
              scoreOver: _vm.computedScoreOver
            },
            on: {
              hideAnswerMark: function($event) {
                _vm.showMarkForm = false
              },
              answerMarkScore: _vm.getScore
            }
          })
        ],
        1
      )
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=template&id=7b88a064&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionInformationBadge.vue?vue&type=template&id=7b88a064&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************/
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=template&id=113c683b&scoped=true&":
/*!***************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=template&id=113c683b&scoped=true& ***!
  \***************************************************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "p-2" },
    [
      _c("assessment-section-information-badge", {
        attrs: { assessmentSection: _vm.assessmentSection }
      }),
      _vm._v(" "),
      _c(
        "div",
        {
          staticClass:
            "h-full max-h-3/4 flex-shrink mb-2 overflow-y-auto p-2 overflow-x-hidden"
        },
        _vm._l(_vm.answers, function(answer) {
          return _c("answer-marking-badge", {
            key: answer.id,
            attrs: {
              answer: answer,
              providedMark: _vm.getSpecificMark(answer)
            },
            on: { marked: _vm.marked }
          })
        }),
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=template&id=3697b187&scoped=true&":
/*!***********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=template&id=3697b187&scoped=true& ***!
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
  return _vm.question
    ? _c(
        "div",
        {
          staticClass: "question-badge-wrapper relative",
          class: { "mx-2.5": !_vm.computedClasses.includes("mx") }
        },
        [
          _c(
            "div",
            { staticClass: "other" },
            [
              _vm.close
                ? _c("font-awesome-icon", {
                    staticClass: "close",
                    attrs: { icon: ["fa", "times"] },
                    on: { click: _vm.clickedClose }
                  })
                : _vm._e(),
              _vm._v(" "),
              _vm.drag
                ? _c(
                    "div",
                    {
                      staticClass: "drag",
                      attrs: { draggable: "" },
                      on: { click: _vm.clickedDrag, dragstart: _vm.clickedDrag }
                    },
                    [
                      _c("font-awesome-icon", {
                        attrs: { icon: ["fa", "hand-rock"] }
                      })
                    ],
                    1
                  )
                : _vm._e()
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "main",
              class: [_vm.backgroundClass ? _vm.backgroundClass : "bg-wheat"],
              on: { dblclick: _vm.dbclickedQuestion }
            },
            [
              _c("div", { staticClass: "body" }, [
                _vm._v(
                  "\n            " + _vm._s(_vm.question.body) + "\n        "
                )
              ]),
              _vm._v(" "),
              !_vm.noFiles && _vm.computedFiles.length
                ? _c(
                    "div",
                    { staticClass: "file" },
                    [
                      _c("file-preview", {
                        attrs: { file: _vm.computedFiles[0], showRemove: false }
                      })
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.question.hint.length
                ? _c("div", { staticClass: "hint" }, [
                    _vm._v(
                      "\n            hint: " +
                        _vm._s(_vm.question.hint) +
                        "\n        "
                    )
                  ])
                : _vm._e(),
              _vm._v(" "),
              _vm.question.scoreOver.length
                ? _c("div", { staticClass: "score" }, [
                    _vm._v(
                      "\n            " +
                        _vm._s("score over: " + _vm.question.scoreOver) +
                        "\n        "
                    )
                  ])
                : _vm._e(),
              _vm._v(" "),
              _vm.question.possibleAnswers.length
                ? _c(
                    "div",
                    { staticClass: "possible-answers" },
                    [
                      _c("span", [_vm._v("Options:")]),
                      _vm._v(" "),
                      _vm._l(_vm.question.possibleAnswers, function(
                        possibleAnswer,
                        index
                      ) {
                        return _c("possible-answer-badge", {
                          key: index,
                          attrs: {
                            possibleAnswer: possibleAnswer,
                            drag: false,
                            answerType: _vm.question.answerType
                          }
                        })
                      })
                    ],
                    2
                  )
                : _vm._e()
            ]
          )
        ]
      )
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=template&id=8e10cdbe&scoped=true&":
/*!**********************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=template&id=8e10cdbe&scoped=true& ***!
  \**********************************************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "w-full mt-4" },
    [
      _c("assessment-section-marking-badge", {
        staticClass: "min-h-90vh",
        attrs: {
          assessmentSection: _vm.currentAssessmentSection,
          answers: _vm.computedAnswers,
          marks: _vm.computedMarks
        },
        on: { marked: _vm.marked }
      }),
      _vm._v(" "),
      _c("navigation-buttons", {
        attrs: {
          isFirst: _vm.computedIsFirstSection,
          isLast: _vm.computedIsLastSection
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

/***/ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(/*! !!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true&");
if(content.__esModule) content = content.default;
if(typeof content === 'string') content = [[module.id, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var add = __webpack_require__(/*! !../../../node_modules/vue-style-loader/lib/addStylesClient.js */ "./node_modules/vue-style-loader/lib/addStylesClient.js").default
var update = add("31855637", content, false, {});
// Hot Module Replacement
if(false) {}

/***/ }),

/***/ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-style-loader/index.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(/*! !!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true&");
if(content.__esModule) content = content.default;
if(typeof content === 'string') content = [[module.id, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var add = __webpack_require__(/*! !../../../../node_modules/vue-style-loader/lib/addStylesClient.js */ "./node_modules/vue-style-loader/lib/addStylesClient.js").default
var update = add("89103194", content, false, {});
// Hot Module Replacement
if(false) {}

/***/ })

}]);