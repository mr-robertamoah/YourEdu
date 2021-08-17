"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["Testing"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentDetails.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentDetails.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
/* harmony import */ var _profile_ProfilePicture_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./profile/ProfilePicture.vue */ "./resources/js/components/profile/ProfilePicture.vue");
/* harmony import */ var _RadioInput_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./RadioInput.vue */ "./resources/js/components/RadioInput.vue");
/* harmony import */ var _SpecialButton_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./SpecialButton.vue */ "./resources/js/components/SpecialButton.vue");
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

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




/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    ProfilePicture: _profile_ProfilePicture_vue__WEBPACK_IMPORTED_MODULE_0__.default,
    SpecialButton: _SpecialButton_vue__WEBPACK_IMPORTED_MODULE_2__.default,
    RadioInput: _RadioInput_vue__WEBPACK_IMPORTED_MODULE_1__.default
  },
  props: {
    assessment: {
      type: Object,
      "default": function _default() {
        return null;
      },
      required: true
    }
  },
  data: function data() {
    return {
      filter: 'all'
    };
  },
  computed: _objectSpread(_objectSpread({}, (0,vuex__WEBPACK_IMPORTED_MODULE_3__.mapGetters)(['getUser'])), {}, {
    computedFilteredAllParticipants: function computedFilteredAllParticipants() {
      if (this.filter == 'all') {
        return this.computedAllParticipants;
      }

      if (this.filter == 'markers') {
        return this.computedMarkers;
      }

      if (this.filter == 'participants') {
        return this.computedParticipants;
      }
    },
    computedMarkers: function computedMarkers() {
      return this.assessment.markers.map(function (marker) {
        var newMarker = _.cloneDeep(marker);

        newMarker.isMarker = true;
        return newMarker;
      });
    },
    computedParticipants: function computedParticipants() {
      return this.assessment.participants.map(function (participant) {
        var newParticipant = _.cloneDeep(participant);

        newParticipant.isParticipant = true;
        return newParticipant;
      });
    },
    computedAllParticipants: function computedAllParticipants() {
      var _this = this;

      var participants = _.uniqWith([this.assessment.addedby].concat(_toConsumableArray(this.computedMarkers), _toConsumableArray(this.computedParticipants)), function (firstAccount, secondAccount) {
        return _this.areSameAccount(firstAccount, secondAccount);
      });

      return participants.map(function (participant) {
        var newParticipant = _.cloneDeep(participant);

        if (_this.isAddedby(newParticipant)) {
          newParticipant.isAddedby = true;
        }

        return newParticipant;
      });
    },
    computedParticipant: function computedParticipant() {
      var _this2 = this;

      return this.computedAllParticipants.find(function (participant) {
        var _this2$getUser;

        return participant.userId == ((_this2$getUser = _this2.getUser) === null || _this2$getUser === void 0 ? void 0 : _this2$getUser.id);
      });
    },
    computedCanMark: function computedCanMark() {
      var _this$computedPartici, _this$computedPartici2;

      return ((_this$computedPartici = this.computedParticipant) === null || _this$computedPartici === void 0 ? void 0 : _this$computedPartici.isMarker) || ((_this$computedPartici2 = this.computedParticipant) === null || _this$computedPartici2 === void 0 ? void 0 : _this$computedPartici2.isAddedby);
    },
    computedFilterMessage: function computedFilterMessage() {
      return this.filter === 'all' ? 'these are all the user accounts invovled in this assessment 😎' : this.filter === 'markers' ? 'these user accounts will be marking the all the submitted works 😐' : this.filter === 'participants' ? 'these are the accounts to take the assessment and have their work submitted and marked 😁' : '';
    }
  }),
  methods: {
    clickedButton: function clickedButton(text, account) {
      this.$emit('clickedButton', {
        text: text,
        account: account
      });
    },
    isAddedbyOrMarker: function isAddedbyOrMarker(account) {
      if (this.isAddedby(account)) {
        return true;
      }

      if (this.isMarker(account)) {
        return true;
      }

      return false;
    },
    isAddedby: function isAddedby(account) {
      if (this.areSameAccount(account, this.assessment.addedby)) {
        return true;
      }

      return false;
    },
    isMarker: function isMarker(account) {
      var _this3 = this;

      this.assessment.markers.forEach(function (marker) {
        if (_this3.areSameAccount(account, marker)) {
          return true;
        }
      });
      return false;
    },
    areSameAccount: function areSameAccount(firstAccount, secondAccount) {
      return firstAccount.account === secondAccount.account && firstAccount.accountId === secondAccount.accountId;
    },
    areNotSameAccount: function areNotSameAccount(firstAccount, secondAccount) {
      return !this.areSameAccount(firstAccount, secondAccount);
    },
    canMark: function canMark(account) {
      var _this$getUser;

      if (!this.computedCanMark) {
        return false;
      }

      if (this.areSameAccount(this.computedParticipant, account)) {
        return false;
      }

      if (account.userId == ((_this$getUser = this.getUser) === null || _this$getUser === void 0 ? void 0 : _this$getUser.id)) {
        return false;
      }

      return true;
    },
    getDetails: function getDetails(account) {
      var msg = '';

      if (account.isAddedby) {
        msg += "owner of assessment";
      }

      if (account.isMarker) {
        if (msg.length) msg += ', ';
        msg += "marker of submitted works";
      }

      if (account.isParticipant) {
        if (msg.length) msg += ', ';
        msg += "participant of assessment";
      }

      return msg;
    },
    isYourAccount: function isYourAccount(account) {
      var _this$getUser2;

      return ((_this$getUser2 = this.getUser) === null || _this$getUser2 === void 0 ? void 0 : _this$getUser2.id) === account.userId;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentSingle.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentSingle.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _SpecialButton__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SpecialButton */ "./resources/js/components/SpecialButton.vue");
/* harmony import */ var _ItemViewCover__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ItemViewCover */ "./resources/js/components/ItemViewCover.vue");
/* harmony import */ var _profile_ProfilePicture__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./profile/ProfilePicture */ "./resources/js/components/profile/ProfilePicture.vue");
/* harmony import */ var _dashboard_AssessmentSectionMiniBadge__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./dashboard/AssessmentSectionMiniBadge */ "./resources/js/components/dashboard/AssessmentSectionMiniBadge.vue");
/* harmony import */ var _dashboard_QuestionAnsweringBadge__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./dashboard/QuestionAnsweringBadge */ "./resources/js/components/dashboard/QuestionAnsweringBadge.vue");
/* harmony import */ var _ItemRequestSection__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./ItemRequestSection */ "./resources/js/components/ItemRequestSection.vue");
/* harmony import */ var vue_spinner_src_PulseLoader__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! vue-spinner/src/PulseLoader */ "./node_modules/vue-spinner/src/PulseLoader.vue");
/* harmony import */ var _transitions_FadeUp__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./transitions/FadeUp */ "./resources/js/components/transitions/FadeUp.vue");
/* harmony import */ var _mixins_Alert_mixin__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../mixins/Alert.mixin */ "./resources/js/mixins/Alert.mixin.js");
/* harmony import */ var _mixins_Like_mixin__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../mixins/Like.mixin */ "./resources/js/mixins/Like.mixin.js");
/* harmony import */ var _mixins_Flag_mixin__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../mixins/Flag.mixin */ "./resources/js/mixins/Flag.mixin.js");
/* harmony import */ var _mixins_Save_mixin__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ../mixins/Save.mixin */ "./resources/js/mixins/Save.mixin.js");
/* harmony import */ var _mixins_Profiles_mixin__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ../mixins/Profiles.mixin */ "./resources/js/mixins/Profiles.mixin.js");
/* harmony import */ var _mixins_Timing_mixin__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ../mixins/Timing.mixin */ "./resources/js/mixins/Timing.mixin.js");
/* harmony import */ var _mixins_PopUp_mixin__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ../mixins/PopUp.mixin */ "./resources/js/mixins/PopUp.mixin.js");
/* harmony import */ var _mixins_Participation_mixin__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ../mixins/Participation.mixin */ "./resources/js/mixins/Participation.mixin.js");
/* harmony import */ var _mixins_SmallModal_mixin__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ../mixins/SmallModal.mixin */ "./resources/js/mixins/SmallModal.mixin.js");
/* harmony import */ var _mixins_Storage_mixin__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ../mixins/Storage.mixin */ "./resources/js/mixins/Storage.mixin.js");
/* harmony import */ var _mixins_Comments_mixin__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! ../mixins/Comments.mixin */ "./resources/js/mixins/Comments.mixin.js");
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_24__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
/* harmony import */ var _forms_AssessmentSectionAnsweringForm_vue__WEBPACK_IMPORTED_MODULE_20__ = __webpack_require__(/*! ./forms/AssessmentSectionAnsweringForm.vue */ "./resources/js/components/forms/AssessmentSectionAnsweringForm.vue");
/* harmony import */ var _forms_AssessmentSectionMarkingForm_vue__WEBPACK_IMPORTED_MODULE_21__ = __webpack_require__(/*! ./forms/AssessmentSectionMarkingForm.vue */ "./resources/js/components/forms/AssessmentSectionMarkingForm.vue");
/* harmony import */ var _AssessmentDetails_vue__WEBPACK_IMPORTED_MODULE_22__ = __webpack_require__(/*! ./AssessmentDetails.vue */ "./resources/js/components/AssessmentDetails.vue");
/* harmony import */ var _PostButton_vue__WEBPACK_IMPORTED_MODULE_23__ = __webpack_require__(/*! ./PostButton.vue */ "./resources/js/components/PostButton.vue");


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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    QuestionAnsweringBadge: _dashboard_QuestionAnsweringBadge__WEBPACK_IMPORTED_MODULE_5__.default,
    AssessmentSectionMiniBadge: _dashboard_AssessmentSectionMiniBadge__WEBPACK_IMPORTED_MODULE_4__.default,
    ItemViewCover: _ItemViewCover__WEBPACK_IMPORTED_MODULE_2__.default,
    SpecialButton: _SpecialButton__WEBPACK_IMPORTED_MODULE_1__.default,
    FadeUp: _transitions_FadeUp__WEBPACK_IMPORTED_MODULE_8__.default,
    ProfilePicture: _profile_ProfilePicture__WEBPACK_IMPORTED_MODULE_3__.default,
    AssessmentSectionAnsweringForm: _forms_AssessmentSectionAnsweringForm_vue__WEBPACK_IMPORTED_MODULE_20__.default,
    AssessmentSectionMarkingForm: _forms_AssessmentSectionMarkingForm_vue__WEBPACK_IMPORTED_MODULE_21__.default,
    ItemRequestSection: _ItemRequestSection__WEBPACK_IMPORTED_MODULE_6__.default,
    PulseLoader: vue_spinner_src_PulseLoader__WEBPACK_IMPORTED_MODULE_7__.default,
    AssessmentDetails: _AssessmentDetails_vue__WEBPACK_IMPORTED_MODULE_22__.default,
    PostButton: _PostButton_vue__WEBPACK_IMPORTED_MODULE_23__.default
  },
  props: {
    assessment: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    schoolAdmin: {
      type: Object,
      "default": function _default() {
        return null;
      }
    }
  },
  mixins: [_mixins_Alert_mixin__WEBPACK_IMPORTED_MODULE_9__.default, _mixins_Like_mixin__WEBPACK_IMPORTED_MODULE_10__.default, _mixins_Flag_mixin__WEBPACK_IMPORTED_MODULE_11__.default, _mixins_Save_mixin__WEBPACK_IMPORTED_MODULE_12__.default, _mixins_Storage_mixin__WEBPACK_IMPORTED_MODULE_18__.default, _mixins_Comments_mixin__WEBPACK_IMPORTED_MODULE_19__.default, _mixins_Participation_mixin__WEBPACK_IMPORTED_MODULE_16__.default, _mixins_Profiles_mixin__WEBPACK_IMPORTED_MODULE_13__.default, _mixins_Timing_mixin__WEBPACK_IMPORTED_MODULE_14__.default, _mixins_PopUp_mixin__WEBPACK_IMPORTED_MODULE_15__.default, _mixins_SmallModal_mixin__WEBPACK_IMPORTED_MODULE_17__.default],
  data: function data() {
    return {
      steps: 0,
      assessmentFull: false,
      showRequest: false,
      loading: false,
      work: null,
      answers: [],
      marks: [],
      viewHistory: [0],
      answeringOrMarkingType: 'all'
    };
  },
  watch: {
    steps: function steps(newValue) {
      if (newValue === 0) {
        this.clearViewHistory();
      }
    },
    "assessment.likes": {
      immediate: true,
      handler: function handler(newValue) {
        if (newValue) {
          this.likeData.likes = newValue.length;
        }
      }
    },
    "assessment.saves": {
      immediate: true,
      handler: function handler(newValue) {
        if (newValue) {
          this.saveData.saves = newValue.length;
        }
      }
    }
  },
  mounted: function mounted() {
    this.setStorage();
    this.setMyFlag();
    this.setMyLike();
    this.setMySave();
    this.listen();
    this.listenForComments();
    this.listenForLikes();
    this.listenForFlags();
    this.listenForSaves();
    this.listenForParticipation();
  },
  beforeDestroy: function beforeDestroy() {
    this.unsetStorage();
  },
  computed: _objectSpread(_objectSpread({}, (0,vuex__WEBPACK_IMPORTED_MODULE_24__.mapGetters)(['getUser'])), {}, {
    computedCoverData: function computedCoverData() {
      return {
        name: this.assessment.name,
        description: this.assessment.description ? this.assessment.description : '',
        type: 'assessment'
      };
    },
    computedNumberOfQuestions: function computedNumberOfQuestions() {
      return this.assessment.assessmentSections.reduce(function (sum, section) {
        return sum + section.questions.length;
      }, 0);
    },
    computedNumberOfQuestionsToAnswer: function computedNumberOfQuestionsToAnswer() {
      return this.computedNumberOfQuestions - this.computedWorkAnswers.length;
    },
    computedItem: function computedItem() {
      return {
        itemId: this.assessment.id,
        item: 'assessment'
      };
    },
    computedItemable: function computedItemable() {
      return this.assessment ? this.assessment : {};
    },
    computedIsOwner: function computedIsOwner() {
      var _this$getUser;

      return this.assessment.addedby.userId === ((_this$getUser = this.getUser) === null || _this$getUser === void 0 ? void 0 : _this$getUser.id);
    },
    computedAccount: function computedAccount() {
      return this.computedIsOwner ? this.assessment.addedby : this.computedParticipant ? this.computedParticipant : null;
    },
    computedRestricted: function computedRestricted() {
      return !this.computedParticipant ? false : this.computedParticipant.state === 'RESTRICTED' || this.computedParticipant.state === 'BANNED';
    },
    computedBanned: function computedBanned() {
      return !this.computedParticipant ? false : this.computedParticipant.state === 'BANNED';
    },
    computedParticipantsInfo: function computedParticipantsInfo() {
      return this.computedParticipantsNumber === 1 ? "".concat(this.computedParticipantsNumber, " participant") : "".concat(this.computedParticipantsNumber, " participants");
    },
    computedParticipantsNumber: function computedParticipantsNumber() {
      return this.assessment.participants.length + 1;
    },
    computedHasAnswered: function computedHasAnswered() {
      var _this = this;

      return this.assessment.answeredbyUserIds.findIndex(function (userId) {
        return userId == _this.getUser.id;
      }) > -1;
    },
    computedCanParticipate: function computedCanParticipate() {
      if (this.computedParticipant || this.computedHasAnswered) {
        return false;
      }

      if (this.computedIsOwner) {
        return false;
      }

      if (this.getProfiles) {
        return true;
      }

      return false;
    },
    computedMarkables: function computedMarkables() {
      if (!this.getProfiles) {
        return [];
      }

      if (this.computedMarker) {
        return [];
      }

      var profiles = this.getProfiles.filter(function (profile) {
        return ['facilitator', 'professional'].includes(profile.account);
      });

      if (profiles.length) {
        return profiles;
      }

      if (this.computedIsOwner) {
        return [this.assessment.addedby];
      }

      return [];
    },
    computedMarker: function computedMarker() {
      var _this$assessment$mark,
          _this2 = this;

      if (!this.getUser) {
        return null;
      }

      var index = (_this$assessment$mark = this.assessment.markers) === null || _this$assessment$mark === void 0 ? void 0 : _this$assessment$mark.findIndex(function (participant) {
        return participant.userId === _this2.getUser.id;
      });

      if (index > -1) {
        return this.assessment.markers[index];
      }

      return null;
    },
    computedParticipant: function computedParticipant() {
      var _this$assessment$part,
          _this3 = this;

      if (this.computedIsOwner) {
        return this.assessment.addedby;
      }

      if (!this.getUser) {
        return null;
      }

      var index = (_this$assessment$part = this.assessment.participants) === null || _this$assessment$part === void 0 ? void 0 : _this$assessment$part.findIndex(function (participant) {
        return participant.userId === _this3.getUser.id;
      });

      if (index > -1) {
        return this.assessment.participants[index];
      }

      return null;
    },
    computedAnswersWithoutFiles: function computedAnswersWithoutFiles() {
      var _this4 = this;

      return this.answers.map(function (answer) {
        return _this4.mappedItem(answer);
      });
    },
    computedUserParticipant: function computedUserParticipant() {
      return this.computedIsOwner || this.computedParticipant ? true : false;
    },
    computedCanJoin: function computedCanJoin() {
      return !this.computedPendingParticipant && !this.computedUserParticipant && !this.computedHasAnswered && this.assessment.type === 'PUBLIC';
    },
    computedUnSentAnswers: function computedUnSentAnswers() {
      return this.answeringOrMarkingType === 'all' ? [] : this.answers.filter(function (answer) {
        return !answer.isSent;
      });
    },
    computedWorkAnswers: function computedWorkAnswers() {
      return this.work ? this.work.answers : [];
    },
    computedWorkAnswersWithoutMark: function computedWorkAnswersWithoutMark() {
      return this.computedWorkAnswers.filter(function (answer) {
        return !answer.mark;
      });
    },
    computedShowSubmitMarks: function computedShowSubmitMarks() {
      return this.computedWorkAnswersWithoutMark.length && this.marks.length;
    },
    computedItemableStorageKey: function computedItemableStorageKey() {
      return "".concat(this.computedItem.item).concat(this.computedItem.itemId);
    }
  }),
  methods: _objectSpread(_objectSpread({}, (0,vuex__WEBPACK_IMPORTED_MODULE_24__.mapActions)(['home/removeAssessment', 'home/replaceAssessment', 'profile/removeAssessment', 'profile/replaceAssessment', 'dashboard/removeAssessment', 'dashboard/replaceAssessment', 'dashboard/deleteAssessment', 'dashboard/answerAssessment', 'dashboard/getWork', 'dashboard/markAssessment', 'profile/deleteMark', 'profile/updateMark', 'profile/deleteAnswer', 'profile/updateAnswer'])), {}, {
    listen: function listen() {
      var _this5 = this;

      Echo.channel("youredu.assessment.".concat(this.assessment.id)).listen('.updateAssessment', function (data) {
        _this5["".concat(_this5.$route.name, "/replaceAssessment")](data.assessment);
      }).listen('.deleteAssessment', function (data) {
        _this5["".concat(_this5.$route.name, "/removeAssessment")](data);
      });
    },
    goBack: function goBack() {
      this.goToPreviousView();
      this.removeLastView();
    },
    goToPreviousView: function goToPreviousView() {
      var index = this.viewHistory.length - 2;

      if (index < 0) {
        index = 0;
      }

      this.goToStep(this.viewHistory[index], false);
    },
    removeLastView: function removeLastView() {
      this.viewHistory.pop();
    },
    clearViewHistory: function clearViewHistory() {
      this.viewHistory = [0];
    },
    addStepToViewHistory: function addStepToViewHistory() {
      this.viewHistory.push(this.steps);
    },
    handleGoToStep: function handleGoToStep(number) {
      if (number == 0) {
        this.answerAssessment({
          done: true
        });
      }

      this.removeLastView();
      this.goToStep(number);
    },
    setStorage: function setStorage() {
      this.getStorageData();
    },
    unsetStorage: function unsetStorage() {
      if (this.filterNotSent(this.answers).length) {
        this.setStorageData('answers');
      }

      if (this.filterNotSent(this.marks).length) {
        this.setStorageData('marks');
      }
    },
    filterNotSent: function filterNotSent(items) {
      return items.filter(function (item) {
        return !item.isSent;
      });
    },
    getStorageDataObject: function getStorageDataObject() {
      var data = this.getItem(this.computedItemableStorageKey);

      if (!data) {
        data = {};
      }

      return data;
    },
    getStorageData: function getStorageData() {
      var data = this.getStorageDataObject();

      if (data.answers) {
        this.answers = data.answers;
      }

      if (data.marks) {
        this.marks = data.marks;
      }
    },
    setStorageData: function setStorageData(what) {
      var data = this.getStorageDataObject();

      if (what === 'answers') {
        data.answers = this.answers;
      }

      if (what === 'marks') {
        data.marks = this.marks;
      }

      this.setItem(this.computedItemableStorageKey, data);
    },
    deletePartOfStorageData: function deletePartOfStorageData(what) {
      var data = this.getStorageDataObject();

      if (what === 'answers') {
        delete data.answers;
      }

      if (what === 'marks') {
        delete data.marks;
      }

      this.setItem(this.computedItemableStorageKey, data);
    },
    removeStorageData: function removeStorageData() {
      this.removeItem(this.computedItemableStorageKey);
    },
    initiatedAssessmentSection: function initiatedAssessmentSection(section) {
      var index = this.assessment.assessmentSections.findIndex(function (assessmentSection) {
        return section.id === assessmentSection.id;
      });

      if (index === -1) {
        return;
      }

      this.assessment.assessmentSections[index].initiated = true;
    },
    handleFewMinutesMore: function handleFewMinutesMore(data) {
      this.issueDangerAlert({
        message: "less than five minites left"
      });
    },
    handleNoTimeLeft: function handleNoTimeLeft(data) {
      var _this6 = this;

      if ((data === null || data === void 0 ? void 0 : data.item) === 'assessmentSection') {
        return;
      }

      this.noTimeLeftHandled = true;

      if (this.computedHasAnswered) {
        return;
      }

      if (this.answeringOrMarkingType != 'all') {
        this.computedUnSentAnswers.forEach(function (answer) {
          _this6.sendAnswer(answer);
        });
      }

      if (this.answeringOrMarkingType == 'all') {
        this.answerAssessment({
          done: false,
          dontCheck: true
        });
      }

      this.answerAssessment({
        done: true
      });
    },
    answered: function answered(data) {
      if (!['update', 'delete'].includes(data.type)) {
        this.addAnswer(data);
        this.storeItems('answers');
        this.sendAnswer(data);
        return;
      }

      if (data.type === 'update') {
        this.updateAnswer(data);
        return;
      }

      this.showProfilesAction = 'delete answer';
      this.issueCustomMessage({
        message: 'are you sure you want to delete this answer?',
        data: data,
        type: 'delete'
      });
    },
    addAnswer: function addAnswer(data) {
      var index = this.answers.findIndex(function (answer) {
        return answer.questionId == data.questionId;
      });

      if (index > -1) {
        this.answers.splice(index, 1, this.cloned(data));
        return;
      }

      this.answers.push(this.cloned(data));
    },
    storeItems: function storeItems(type) {
      if (this.answeringOrMarkingType !== 'all') {
        return;
      }

      this.setStorageData(type);
    },
    marked: function marked(data) {
      if (!['update', 'delete'].includes(data.type)) {
        this.addMark(data);
        this.storeItems('marks');
        this.sendMark(data);
        return;
      }

      if (data.type === 'update') {
        this.updateAnswerMark(data);
        return;
      }

      this.showProfilesAction = 'delete mark';
      this.issueCustomMessage({
        message: 'are you sure you want to delete this mark?',
        data: data,
        type: 'delete'
      });
    },
    findIndexOfAnswer: function findIndexOfAnswer(fn) {
      return this.work.answers.findIndex(function (answer) {
        return fn(answer);
      });
    },
    spliceMark: function spliceMark(mark) {
      var replace = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
      var index = this.findIndexOfAnswer(function (answer) {
        var _answer$mark;

        return ((_answer$mark = answer.mark) === null || _answer$mark === void 0 ? void 0 : _answer$mark.id) == mark.id;
      });

      if (replace) {
        this.work.answers[index].mark = mark;
        return;
      }

      this.work.answers[index].mark = null;
    },
    spliceAnswer: function spliceAnswer(answer) {
      var replace = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
      this.spliceItem({
        items: this.work.answers,
        item: answer,
        replace: replace,
        index: this.findIndexOfAnswer(function (ans) {
          return ans.id == (answer === null || answer === void 0 ? void 0 : answer.id);
        })
      });
    },
    spliceItem: function spliceItem(_ref) {
      var items = _ref.items,
          item = _ref.item,
          index = _ref.index,
          replace = _ref.replace;
      if (index === -1) return;

      if (replace) {
        items.splice(index, 1, item);
        return;
      }

      items.splice(index, 1);
    },
    updateAnswerMark: function updateAnswerMark(data) {
      var _this7 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee() {
        var response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _this7.loading = true;
                _context.next = 3;
                return _this7['profile/updateMark']({
                  markId: data.mark.id,
                  data: data.newMarkData
                });

              case 3:
                response = _context.sent;
                _this7.loading = false;

                if (response.status) {
                  _context.next = 8;
                  break;
                }

                _this7.issueDangerAlertForResponse(response);

                return _context.abrupt("return");

              case 8:
                _this7.spliceMark(response.mark, true);

                _this7.removeAssessmentItem(data.newMarkData, 'marks');

              case 10:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },
    deleteAnswerMark: function deleteAnswerMark(data) {
      var _this8 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee2() {
        var response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                _this8.loading = true;
                _context2.next = 3;
                return _this8['profile/deleteMark']({
                  markId: data.mark.id
                });

              case 3:
                response = _context2.sent;
                _this8.loading = false;

                if (response.status) {
                  _context2.next = 8;
                  break;
                }

                _this8.issueDangerAlertForResponse(response, 'oops 😕! something happened. please try again later');

                return _context2.abrupt("return");

              case 8:
                _this8.spliceMark(data.mark);

                _this8.removeAssessmentItem(data.newMarkData, 'marks');

              case 10:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2);
      }))();
    },
    updateAnswer: function updateAnswer(data) {
      var _this9 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee3() {
        var formData, response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee3$(_context3) {
          while (1) {
            switch (_context3.prev = _context3.next) {
              case 0:
                _this9.loading = true;
                formData = new FormData();
                formData.append('account', _this9.computedParticipant.account);
                formData.append('accountId', _this9.computedParticipant.accountId);
                formData.append('item', 'question');
                formData.append('itemId', data.newAnswerData.questionId);
                formData.append('answer', data.newAnswerData.answer);

                if (data.newAnswerData.file) {
                  formData.append('files[]', data.newAnswerData.file);
                }

                formData.append('possibleAnswerIds', JSON.stringify(data.newAnswerData.possibleAnswerIds));
                _context3.next = 11;
                return _this9['profile/updateAnswer']({
                  answerId: data.answer.id,
                  formData: formData
                });

              case 11:
                response = _context3.sent;
                _this9.loading = false;

                if (response.status) {
                  _context3.next = 16;
                  break;
                }

                _this9.issueDangerAlertForResponse(response, 'oops 😕! something happened. please try again later');

                return _context3.abrupt("return");

              case 16:
                _this9.spliceAnswer(response.answer, true);

                _this9.removeAssessmentItem(data.newAnswerData);

              case 18:
              case "end":
                return _context3.stop();
            }
          }
        }, _callee3);
      }))();
    },
    deleteAnswer: function deleteAnswer(data) {
      var _this10 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee4() {
        var response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee4$(_context4) {
          while (1) {
            switch (_context4.prev = _context4.next) {
              case 0:
                _this10.loading = true;
                _context4.next = 3;
                return _this10['profile/deleteAnswer']({
                  answerId: data.answer.id,
                  data: {
                    account: _this10.work.addedby.account,
                    accountId: _this10.work.addedby.accountId,
                    item: 'question',
                    itemId: data.answer.question.id
                  }
                });

              case 3:
                response = _context4.sent;
                _this10.loading = false;

                if (response.status) {
                  _context4.next = 8;
                  break;
                }

                _this10.issueDangerAlertForResponse(response);

                return _context4.abrupt("return");

              case 8:
                _this10.spliceAnswer(data.answer);

                _this10.removeAssessmentItem(data.newAnswerData);

              case 10:
              case "end":
                return _context4.stop();
            }
          }
        }, _callee4);
      }))();
    },
    addMark: function addMark(data) {
      var index = this.marks.findIndex(function (mark) {
        return mark.answerId == data.answerId;
      });

      if (index > -1) {
        this.marks.splice(index, 1, this.cloned(data));
        return;
      }

      this.marks.push(this.cloned(data));
    },
    sendMark: function sendMark(mark) {
      if (this.answeringOrMarkingType === 'all') {
        return;
      }

      this.markAssessment({
        mark: mark,
        done: false,
        dontCheck: false
      });
    },
    cloned: function cloned(data) {
      return _.clone(data);
    },
    sendAnswer: function sendAnswer(answer) {
      if (this.answeringOrMarkingType === 'all') {
        return;
      }

      this.answerAssessment({
        answer: answer,
        done: false,
        dontCheck: false
      });
    },
    mappedItem: function mappedItem(item) {
      var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'answer';
      var data = {};

      if (type == 'answer') {
        var _item$possibleAnswerI;

        data.questionId = item.questionId;

        if (item.answer) {
          data['answer'] = item.answer;
        }

        if ((_item$possibleAnswerI = item.possibleAnswerIds) !== null && _item$possibleAnswerI !== void 0 && _item$possibleAnswerI.length) {
          data['possibleAnswerIds'] = item.possibleAnswerIds;
        }
      }

      if (type == 'mark') {
        data.answerId = item.answerId;
        data.score = item.score;
        data.remark = item.remark;
      }

      return data;
    },
    answerAssessment: function answerAssessment(_ref2) {
      var _this11 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee5() {
        var answer, done, dontCheck, response, formData;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee5$(_context5) {
          while (1) {
            switch (_context5.prev = _context5.next) {
              case 0:
                answer = _ref2.answer, done = _ref2.done, dontCheck = _ref2.dontCheck;
                _this11.loading = true;
                formData = new FormData();
                formData.append('type', _this11.answeringOrMarkingType);
                formData.append('account', _this11.computedParticipant.account);
                formData.append('accountId', _this11.computedParticipant.accountId);

                if (!(_this11.answeringOrMarkingType == 'all' && !done)) {
                  _context5.next = 13;
                  break;
                }

                if (!(_this11.computedNumberOfQuestionsToAnswer > _this11.answers.length && !dontCheck)) {
                  _context5.next = 11;
                  break;
                }

                _this11.issueDangerAlert({
                  message: 'you have not finished answering your questions'
                });

                _this11.loading = false;
                return _context5.abrupt("return");

              case 11:
                formData.append('answers', JSON.stringify(_this11.computedAnswersWithoutFiles));

                _this11.answers.forEach(function (answer) {
                  if (answer.file) {
                    formData.append("answerFile".concat(answer.questionId), answer.file);
                  }
                });

              case 13:
                if (_this11.answeringOrMarkingType != 'all' && answer && !done) {
                  formData.append('answer', JSON.stringify(_this11.mappedItem(answer)));

                  if (answer.file) {
                    formData.append("answerFile".concat(answer.questionId), answer.file);
                  }
                }

                _context5.next = 16;
                return _this11['dashboard/answerAssessment']({
                  assessmentId: _this11.assessment.id,
                  userId: _this11.getUser.id,
                  formData: formData,
                  done: done ? done : false,
                  addUserId: done || _this11.answeringOrMarkingType === 'all'
                });

              case 16:
                response = _context5.sent;
                _this11.loading = false;

                if (response.status) {
                  _context5.next = 21;
                  break;
                }

                _this11.issueDangerAlertForResponse(response);

                return _context5.abrupt("return");

              case 21:
                _this11.updateAnswerOrMarkToSent(answer);

                _this11.alertSuccess = true;

                if (!(_this11.answeringOrMarkingType !== 'all')) {
                  _context5.next = 27;
                  break;
                }

                _this11.alertMessage = "answer sent";

                _this11.updateWork(response.answer);

                return _context5.abrupt("return");

              case 27:
                if (done) {
                  _this11.alertMessage = 'yaay... you are done answering this assessment 😎';
                }

                if (!done) {
                  _this11.alertMessage = "you have successfully sent your answers for this assessment";
                }

                _this11.work = response.work;

                _this11.goToStep(0, false);

                _this11.clearItems();

              case 32:
              case "end":
                return _context5.stop();
            }
          }
        }, _callee5);
      }))();
    },
    updateWork: function updateWork(answer) {
      if (!this.work) {
        return;
      }

      var index = this.work.answers.findIndex(function (ans) {
        return answer.id === ans.id;
      });

      if (index === -1) {
        return;
      }

      this.work.answers.splice(index, 1, answer);
    },
    markAssessment: function markAssessment(_ref3) {
      var _this12 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee6() {
        var mark, done, dontCheck, response, formData;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee6$(_context6) {
          while (1) {
            switch (_context6.prev = _context6.next) {
              case 0:
                mark = _ref3.mark, done = _ref3.done, dontCheck = _ref3.dontCheck;
                _this12.loading = true;
                formData = new FormData();
                formData.append('type', _this12.answeringOrMarkingType);
                formData.append('account', _this12.computedAccount.account);
                formData.append('accountId', _this12.computedAccount.accountId);
                formData.append('workId', _this12.work.id);

                if (!(_this12.answeringOrMarkingType == 'all' && !done)) {
                  _context6.next = 14;
                  break;
                }

                formData.append('userId', _this12.work.addedby.userId);

                if (!(_this12.computedWorkAnswersWithoutMark.length > _this12.marks.length && !dontCheck)) {
                  _context6.next = 13;
                  break;
                }

                _this12.issueDangerAlert({
                  message: 'you have not finished marking your answers'
                });

                _this12.loading = false;
                return _context6.abrupt("return");

              case 13:
                formData.append('marks', JSON.stringify(_this12.marks.map(function (mark) {
                  return _this12.mappedItem(mark, 'mark');
                })));

              case 14:
                if (_this12.answeringOrMarkingType != 'all' && mark && !done) {
                  formData.append('mark', JSON.stringify(_this12.mappedItem(mark, 'mark')));
                }

                _context6.next = 17;
                return _this12['dashboard/markAssessment']({
                  assessmentId: _this12.assessment.id,
                  formData: formData,
                  done: done
                });

              case 17:
                response = _context6.sent;
                _this12.loading = false;

                if (response.status) {
                  _context6.next = 22;
                  break;
                }

                _this12.issueDangerAlertForResponse(response);

                return _context6.abrupt("return");

              case 22:
                _this12.alertSuccess = true;

                if (!done) {
                  _context6.next = 27;
                  break;
                }

                _this12.alertMessage = 'yaay... you are done marking this work 😎';

                _this12.clearItems('marks');

                return _context6.abrupt("return");

              case 27:
                _this12.updateAnswerOrMarkToSent(mark, 'mark');

                if (!(_this12.answeringOrMarkingType !== 'all')) {
                  _context6.next = 31;
                  break;
                }

                _this12.alertMessage = "mark sent";
                return _context6.abrupt("return");

              case 31:
                _this12.alertMessage = "you have successfully sent the marks for this work";
                _this12.work = response.work;

                _this12.clearItems('marks');

              case 34:
              case "end":
                return _context6.stop();
            }
          }
        }, _callee6);
      }))();
    },
    clearItems: function clearItems() {
      var type = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'answers';

      if (!this[type]) {
        return;
      }

      this[type] = [];
      this.deletePartOfStorageData(type);
    },
    goToStep: function goToStep(number) {
      var addToView = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
      this.steps = number;

      if (addToView) {
        this.addStepToViewHistory();
      }
    },
    updateAnswerOrMarkToSent: function updateAnswerOrMarkToSent(item) {
      var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'answer';

      if (!item) {
        return;
      }

      var property = type === 'answer' ? 'questionId' : 'answerId';
      this["".concat(type, "s")].forEach(function (markableItem) {
        if (item[property] == markableItem[property]) {
          markableItem.isSent = true;
        }
      });
    },
    closeItemRequestSection: function closeItemRequestSection() {
      this.showRequest = false;
      this.joinOrInvitationType = '';
    },
    clickedButton: function clickedButton(data) {
      if (this.loading) {
        return;
      }

      if (typeof data !== 'string' && data.text === 'mark work') {
        this.markWork(data.account);
        return;
      }

      if (data === 'take it') {
        this.takeAssessment();
        return;
      }

      if (data === 'start') {
        this.checkPopup();
        return;
      }

      if (data === 'continue') {
        this.startAssessment(true);
        return;
      }

      if (data === 'mark') {
        this.startMarkingAssessment();
        return;
      }

      if (data === 'invite marker') {
        this.requestMarker();
        return;
      }

      if (data === 'view work') {
        // this.startAssessment(true)
        this.viewWork();
        return;
      }

      if (data === 'invite participant') {
        this.requestParticipant();
        return;
      }

      if (data === 'submit answers') {
        this.answerAssessment({
          done: false
        });
        return;
      }

      if (data === 'submit marks') {
        this.markAssessment({
          done: false
        });
        return;
      }

      if (data === 'join markers') {
        this.showProfilesText = 'mark assessment as';
        this.showOnlyProfiles = true;
      }

      if (data === 'want to take') {
        this.showProfilesText = 'take assessment as';
        this.showOnlyProfiles = true;
      }

      this.showProfilesAction = data;
      this.showProfiles = true;
    },
    markWork: function markWork(account) {
      if (this.isAddedbyForWork(account)) {
        this.goToStep(4);
        return;
      }

      this.getWork(account);
    },
    viewWork: function viewWork() {
      this.getWork(this.computedAccount, true);
    },
    isAddedbyForWork: function isAddedbyForWork(account) {
      var _this$work;

      if (!((_this$work = this.work) !== null && _this$work !== void 0 && _this$work.userId)) {
        return false;
      }

      if (account.userId == this.work.addedby.userId) {
        return true;
      }

      return false;
    },
    setWork: function setWork(work) {
      this.work = work;
      this.goToStep(4);
    },
    getWork: function getWork(account) {
      var _arguments = arguments,
          _this13 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee7() {
        var marks, response, data;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee7$(_context7) {
          while (1) {
            switch (_context7.prev = _context7.next) {
              case 0:
                marks = _arguments.length > 1 && _arguments[1] !== undefined ? _arguments[1] : false;
                _this13.loading = true;
                data = {
                  account: account.account,
                  accountId: account.accountId,
                  userId: account.userId
                };

                if (marks) {
                  data.marks = JSON.stringify(marks);
                }

                _context7.next = 6;
                return _this13['dashboard/getWork']({
                  data: data,
                  assessmentId: _this13.assessment.id
                });

              case 6:
                response = _context7.sent;
                _this13.loading = false;

                if (!response.status) {
                  _context7.next = 11;
                  break;
                }

                _this13.setWork(response.work);

                return _context7.abrupt("return");

              case 11:
                _this13.issueDangerAlertForResponse(response, 'oops! something happened 😕');

                _this13.goToStep(0);

              case 13:
              case "end":
                return _context7.stop();
            }
          }
        }, _callee7);
      }))();
    },
    removeAssessmentItem: function removeAssessmentItem(item) {
      var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'answers';

      if (!item) {
        return;
      }

      var index = -1;

      if (type === 'answers') {
        index = this.answers.findIndex(function (answer) {
          return answer.questionId === item.questionId;
        });
      }

      if (type === 'marks') {
        index = this.marks.findIndex(function (mark) {
          return mark.answerId === item.answerId;
        });
      }

      if (index == -1) {
        return;
      }

      this[type].splice(index, 1);
    },
    requestParticipant: function requestParticipant() {
      this.displayRequestSection('participant');
    },
    requestMarker: function requestMarker() {
      this.displayRequestSection('marker');
    },
    displayRequestSection: function displayRequestSection(type) {
      this.showRequest = true;
      this.joinOrInvitationType = type;
    },
    startMarkingAssessment: function startMarkingAssessment() {
      this.goToStep(3);
    },
    takeAssessment: function takeAssessment() {
      this.goToStep(1);
    },
    startAssessment: function startAssessment() {
      var _arguments2 = arguments,
          _this14 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee8() {
        var getWork;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee8$(_context8) {
          while (1) {
            switch (_context8.prev = _context8.next) {
              case 0:
                getWork = _arguments2.length > 0 && _arguments2[0] !== undefined ? _arguments2[0] : false;

                if (!getWork) {
                  _context8.next = 4;
                  break;
                }

                _context8.next = 4;
                return _this14.getWork(_this14.computedAccount);

              case 4:
                _this14.startTimer();

                _this14.goToStep(2);

              case 6:
              case "end":
                return _context8.stop();
            }
          }
        }, _callee8);
      }))();
    },
    clickedPopupResponse: function clickedPopupResponse(data) {
      if (data === 'continue') {
        this.startAssessment();
      }

      this.showPopUp = false;
    },
    checkPopup: function checkPopup() {
      if (this.assessment.timer) {
        this.startAssessment();
        return;
      }

      if (this.assessment.duration) {
        this.showPopUp = true;
        return;
      }

      this.startAssessment();
    },
    clickedMedia: function clickedMedia() {},
    clickedShowPostComments: function clickedShowPostComments() {
      this.$emit('clickedShowPostComments', {
        item: this.post,
        type: 'assessment'
      });
    },
    askLoginRegister: function askLoginRegister(data) {
      this.$emit('askLoginRegister', data);
    },
    postAddComplete: function postAddComplete(data) {
      if (data === 'successful') {
        this.showAddComment = false;
        this.alertSuccess = true;
        this.alertMessage = 'comment created successfully 😎';
        return;
      }

      this.alertDanger = true;
      this.alertMessage = 'comment creation failed 😞';
    },
    clickedAddComment: function clickedAddComment() {
      if (this.computedBanned) return;

      if (!this.getUser) {
        this.$emit('askLoginRegister', 'discussionsingle');
        return;
      }

      if (!this.getProfiles || !this.getProfiles.length) {
        this.issueSmallModalInfoMessage({
          message: 'you must have an account (eg. learner, parent, etc) before you can comment.'
        });
        return;
      }

      this.showAddComment = true;
    },
    clickedProfile: function clickedProfile(data) {
      this.showProfiles = false;

      if (this.showProfilesAction === 'want to take') {
        this.join(data);
        return;
      }

      if (this.showProfilesAction === 'join markers') {
        this.joinOrInvitationType = 'marker';
        this.join(data);
        return;
      }

      if (this.showProfilesAction === 'like') {
        this.like(data);
        return;
      }

      if (this.showProfilesAction === 'save') {
        this.save(data);
        return;
      }

      if (this.showProfilesAction === 'flag') {
        this.issueCustomMessage({
          message: 'are you sure you want to flag this?',
          data: data,
          type: 'delete'
        });
        return;
      }

      if (this.showProfilesAction === 'attach') {
        this.attach(data);
      }
    },
    clickedSmallModalButton: function clickedSmallModalButton(data) {
      if (data === 'ok') {
        return;
      }

      if (data === 'no') {
        this.otherUserAccountLoading = false; //incase this is for leaving or removing participants

        this.clearSmallModal();
        return;
      }

      if (this.showProfilesAction === 'delete') {
        this.deleteDiscussion();
        return;
      }

      if (this.showProfilesAction === 'delete answer') {
        this.deleteAnswer(this.smallModalData);
        return;
      }

      if (this.showProfilesAction === 'delete mark') {
        this.deleteAnswerMark(this.smallModalData);
        return;
      }

      if (this.showProfilesAction === 'participant') {
        this.deleteDiscussionParticipant(this.smallModalData);
        return;
      }

      if (this.showProfilesAction === 'flag') {
        this.flag(this.smallModalData);
      }
    }
  })
});

/***/ }),

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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/GreyButton.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/GreyButton.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var vue_spinner_src_PulseLoader__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-spinner/src/PulseLoader */ "./node_modules/vue-spinner/src/PulseLoader.vue");
//
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
    active: {
      type: Boolean,
      "default": false
    },
    loading: {
      type: Boolean,
      "default": false
    },
    text: {
      type: String,
      "default": ''
    }
  },
  components: {
    PulseLoader: vue_spinner_src_PulseLoader__WEBPACK_IMPORTED_MODULE_0__.default
  },
  data: function data() {
    return {
      clicked: false
    };
  },
  methods: {
    clickedAction: function clickedAction() {
      if (this.active) {
        this.$emit('clickedAction', '');
        return;
      }

      this.$emit('clickedAction', this.text);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemRequestSection.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemRequestSection.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vue_infinite_loading__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-infinite-loading */ "./node_modules/vue-infinite-loading/dist/vue-infinite-loading.js");
/* harmony import */ var vue_infinite_loading__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_infinite_loading__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _SearchInput__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./SearchInput */ "./resources/js/components/SearchInput.vue");
/* harmony import */ var _GreyButton__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./GreyButton */ "./resources/js/components/GreyButton.vue");
/* harmony import */ var vue_spinner_src_PulseLoader__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! vue-spinner/src/PulseLoader */ "./node_modules/vue-spinner/src/PulseLoader.vue");
/* harmony import */ var _discussion_ParticipantBadge__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./discussion/ParticipantBadge */ "./resources/js/components/discussion/ParticipantBadge.vue");
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }



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






/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    SearchInput: _SearchInput__WEBPACK_IMPORTED_MODULE_2__.default,
    GreyButton: _GreyButton__WEBPACK_IMPORTED_MODULE_3__.default,
    ParticipantBadge: _discussion_ParticipantBadge__WEBPACK_IMPORTED_MODULE_5__.default,
    PulseLoader: vue_spinner_src_PulseLoader__WEBPACK_IMPORTED_MODULE_4__.default,
    InfiniteLoader: (vue_infinite_loading__WEBPACK_IMPORTED_MODULE_1___default())
  },
  props: {
    show: {
      type: Boolean,
      "default": false
    },
    loading: {
      type: Boolean,
      "default": false
    },
    allowed: {
      type: String,
      "default": ''
    },
    "for": {
      type: String,
      "default": ''
    },
    hasAllowed: {
      type: Boolean,
      "default": false
    },
    removedParticipant: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    computedItem: {
      type: Object,
      "default": function _default() {
        return null;
      }
    }
  },
  data: function data() {
    return {
      searchType: 'profiles',
      searchLoading: false,
      searchText: '',
      searchParticipants: [],
      searchNextPage: 1,
      noSearchParticipants: true,
      showMoreSearchParticipants: false
    };
  },
  watch: {
    show: {
      immediate: true,
      handler: function handler(newValue) {
        if (!newValue) {
          this.searchParticipants = [];
          return;
        }

        this.setSearchTypeBasedOnAllowed();
      }
    },
    searchType: function searchType(newValue) {
      this.searhInvitableParticipants();
    },
    searchText: function searchText(newValue) {
      if (newValue.length) {
        this.searhInvitableParticipants();
      } else {
        this.searchParticipants = [];
      }
    },
    removedParticipant: function removedParticipant(newValue) {
      if (newValue) {
        this.removeSearchParticipant(newValue.userId);
      }
    },
    searchNextPage: function searchNextPage(newValue) {
      this.setOtherSearchVariables();

      if (newValue == null || newValue == 1) {
        this.showMoreSearchParticipants = false;
        return;
      }

      this.showMoreSearchParticipants = true;
    },
    searchParticipants: function searchParticipants(newValue) {
      this.setOtherSearchVariables();
    }
  },
  computed: {
    computedTitle: function computedTitle() {
      return "invite accounts to join this ".concat(this.computedItem.item);
    }
  },
  methods: _objectSpread(_objectSpread({}, (0,vuex__WEBPACK_IMPORTED_MODULE_6__.mapActions)(['profile/discussionSearch', 'profile/itemSearch'])), {}, {
    clickedParticpantAction: function clickedParticpantAction(data) {
      this.$emit('clickedParticpantAction', data);
    },
    clickedCloseRequest: function clickedCloseRequest() {
      this.clearData();
      this.$emit('clickedCloseRequest');
    },
    clearData: function clearData() {
      this.searchType = 'profiles';
      this.searchLoading = false;
      this.searchText = '';
      this.searchParticipants = [];
      this.searchNextPage = 1;
      this.noSearchParticipants = true;
      this.showMoreSearchParticipants = false;
    },
    searhInvitableParticipants: _.debounce(function () {
      this.searchNextPage = 1;
      this.startSearch();
    }, 400),
    setSearchTypeBasedOnAllowed: function setSearchTypeBasedOnAllowed() {
      if (!this.hasAllowed) {
        this.setSearchType('profiles');
        return;
      }

      if (this.allowed === 'ALL') {
        this.setSearchType('profiles');
        return;
      }

      this.setSearchType(this.allowed.toLowerCase());
    },
    setOtherSearchVariables: function setOtherSearchVariables() {
      if (this.searchNextPage > 1 && this.searchParticipants.length) {
        this.noSearchParticipants = false;
        return;
      }

      if (this.searchNextPage && !this.searchParticipants.length) {
        this.noSearchParticipants = true;
        return;
      }

      if (!this.searchNextPage && this.searchParticipants.length) {
        this.noSearchParticipants = false;
        return;
      }
    },
    clickedSearchType: function clickedSearchType(data) {
      this.setSearchType(data);
    },
    setSearchType: function setSearchType(data) {
      this.searchType = data;
    },
    receivedParticipantsSearchText: function receivedParticipantsSearchText(data) {
      this.searchText = data;
    },
    removeSearchParticipant: function removeSearchParticipant(userId) {
      this.searchParticipants = this.searchParticipants.filter(function (participant) {
        return participant.userId !== userId;
      });
      this.$emit('doneRemovingParticipant');
    },
    startSearch: function startSearch() {
      var _this = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee() {
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _context.next = 2;
                return _this.search();

              case 2:
                _this.searchParticipants = _context.sent;

              case 3:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },
    infiniteHandler: function infiniteHandler($state) {
      var _this2 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee2() {
        var _this2$searchParticip;

        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                _context2.t0 = (_this2$searchParticip = _this2.searchParticipants).push;
                _context2.t1 = _this2$searchParticip;
                _context2.t2 = _toConsumableArray;
                _context2.next = 5;
                return _this2.search();

              case 5:
                _context2.t3 = _context2.sent;
                _context2.t4 = (0, _context2.t2)(_context2.t3);

                _context2.t0.apply.call(_context2.t0, _context2.t1, _context2.t4);

                if (!_this2.searchNextPage) {
                  _context2.next = 11;
                  break;
                }

                $state.loaded();
                return _context2.abrupt("return");

              case 11:
                $state.complete();

              case 12:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2);
      }))();
    },
    search: function search() {
      var _this3 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee3() {
        var response, params, data;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee3$(_context3) {
          while (1) {
            switch (_context3.prev = _context3.next) {
              case 0:
                if (!(_this3.searchNextPage === null || !_this3.searchText.length)) {
                  _context3.next = 2;
                  break;
                }

                return _context3.abrupt("return");

              case 2:
                _this3.searchLoading = true;
                response = null, params = {
                  search: _this3.searchText,
                  searchType: _this3.searchType
                }, data = {};

                if (_this3["for"].length) {
                  params["".concat(_this3["for"], "s")] = true;
                }

                data.nextPage = _this3.searchNextPage;
                data.item = _this3.computedItem.item;
                data.params = params;
                data.params["".concat(_this3.computedItem.item, "Id")] = _this3.computedItem.itemId;
                _context3.next = 11;
                return _this3["profile/itemSearch"](data);

              case 11:
                response = _context3.sent;
                _this3.searchLoading = false;

                if (response.status) {
                  _context3.next = 15;
                  break;
                }

                return _context3.abrupt("return", []);

              case 15:
                if (response.next) {
                  _this3.searchNextPage += 1;
                }

                if (!response.next) {
                  _this3.searchNextPage = null;
                }

                return _context3.abrupt("return", response.data);

              case 18:
              case "end":
                return _context3.stop();
            }
          }
        }, _callee3);
      }))();
    }
  })
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemViewCover.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemViewCover.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************/
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    data: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    additionalText: {
      type: String,
      "default": ''
    },
    transparent: {
      type: Boolean,
      "default": false
    }
  },
  computed: {
    computedTypeText: function computedTypeText() {
      return this.data.type ? "this is an ".concat(this.data.type) : '';
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LessonBoard.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LessonBoard.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _TheBoard_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./TheBoard.vue */ "./resources/js/components/TheBoard.vue");
/* harmony import */ var _TextInput_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TextInput.vue */ "./resources/js/components/TextInput.vue");
/* harmony import */ var _specials_ResizingComponent_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./specials/ResizingComponent.vue */ "./resources/js/components/specials/ResizingComponent.vue");
/* harmony import */ var _MainCheckbox_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./MainCheckbox.vue */ "./resources/js/components/MainCheckbox.vue");
/* harmony import */ var _mixins_PopUp_mixin__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../mixins/PopUp.mixin */ "./resources/js/mixins/PopUp.mixin.js");
/* harmony import */ var _mixins_Alert_mixin__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../mixins/Alert.mixin */ "./resources/js/mixins/Alert.mixin.js");
/* harmony import */ var _MainSelect_vue__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./MainSelect.vue */ "./resources/js/components/MainSelect.vue");
/* harmony import */ var _NumberInput_vue__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./NumberInput.vue */ "./resources/js/components/NumberInput.vue");
/* harmony import */ var _RadioInput_vue__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./RadioInput.vue */ "./resources/js/components/RadioInput.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    TheBoard: _TheBoard_vue__WEBPACK_IMPORTED_MODULE_0__.default,
    ResizingComponent: _specials_ResizingComponent_vue__WEBPACK_IMPORTED_MODULE_2__.default,
    TextInput: _TextInput_vue__WEBPACK_IMPORTED_MODULE_1__.default,
    MainCheckbox: _MainCheckbox_vue__WEBPACK_IMPORTED_MODULE_3__.default,
    MainSelect: _MainSelect_vue__WEBPACK_IMPORTED_MODULE_6__.default,
    NumberInput: _NumberInput_vue__WEBPACK_IMPORTED_MODULE_7__.default,
    RadioInput: _RadioInput_vue__WEBPACK_IMPORTED_MODULE_8__.default
  },
  props: {
    colors: {
      type: Array,
      "default": function _default() {
        return [{
          name: 'red',
          value: '#FF0000'
        }, {
          name: 'yellow',
          value: '#FFFF00'
        }, {
          name: 'green',
          value: '#008000'
        }, {
          name: 'blue',
          value: '#0000FF'
        }, {
          name: 'gray',
          value: '#808080'
        }, {
          name: 'indigo',
          value: '#4B0082'
        }, {
          name: 'purple',
          value: '#800080'
        }, {
          name: 'pink',
          value: '#FFC0CB'
        }];
      }
    }
  },
  mixins: [_mixins_Alert_mixin__WEBPACK_IMPORTED_MODULE_5__.default, _mixins_PopUp_mixin__WEBPACK_IMPORTED_MODULE_4__.default],
  data: function data() {
    return {
      accounts: [],
      boards: [],
      board: {
        id: null,
        name: ''
      },
      showItem: '',
      selectedColor: null,
      lineWidth: 1,
      resize: false,
      move: false,
      resizedBoard: {},
      responses: ['ok', 'cancel'],
      defaultResponse: 'cancel',
      drawObject: {
        sides: 3,
        polygon: false,
        line: false,
        angle: 0,
        circle: false,
        freehand: true
      }
    };
  },
  watch: {
    showItem: function showItem(newValue) {
      var _this = this;

      if (newValue === 'colors') {
        setTimeout(function () {
          _this.showItem = '';
        }, 4000);
      }
    },
    selectedColor: function selectedColor(newValue) {
      if (newValue) {
        this.setActiveColor(newValue);
      }
    },
    "board.name": function boardName(newValue) {
      if (newValue !== null && newValue !== void 0 && newValue.length) {
        this.showPopUp = true;
      }
    },
    "drawObject.line": function drawObjectLine(newValue) {
      if (newValue) {
        this.drawObject.circle = false;
        this.drawObject.polygon = false;
        this.drawObject.freehand = false;
      }
    },
    "drawObject.circle": function drawObjectCircle(newValue) {
      if (newValue) {
        this.drawObject.line = false;
        this.drawObject.polygon = false;
        this.drawObject.freehand = false;
      }
    },
    "drawObject.polygon": function drawObjectPolygon(newValue) {
      if (newValue) {
        this.drawObject.line = false;
        this.drawObject.circle = false;
        this.drawObject.freehand = false;
      }
    },
    "drawObject.freehand": function drawObjectFreehand(newValue) {
      if (newValue) {
        this.drawObject.line = false;
        this.drawObject.circle = false;
        this.drawObject.polygon = false;
      }
    }
  },
  mounted: function mounted() {
    this.selectedColor = '#000000';
    this.backgroundColor = '#ffffff';
  },
  computed: {
    computedBoardNameExists: function computedBoardNameExists() {
      var _this2 = this;

      return this.boards.findIndex(function (board) {
        return board.name === _this2.board.name;
      }) > -1;
    },
    computedDrawWhat: function computedDrawWhat() {
      if (this.drawObject.line) {
        return 'line';
      }

      if (this.drawObject.circle) {
        return 'circle';
      }

      if (this.drawObject.polygon) {
        return 'polygon';
      }

      return 'freehand';
    }
  },
  methods: {
    startedResizing: function startedResizing(_ref) {
      var theBoard = _ref.componentInstance;
      this.resizedBoard = theBoard.board;
    },
    stoppedResizing: function stoppedResizing(data) {
      this.resizedBoard = {};
    },
    stopResizingAndMoving: function stopResizingAndMoving() {
      this.resize = false;
      this.move = false;
    },
    clickedColor: function clickedColor(value) {
      this.selectedColor = value;
      this.showPopUp = false;
    },
    setActiveColor: function setActiveColor(color) {
      this.$refs.activecolor.style.background = color;
    },
    clickedPopupResponse: function clickedPopupResponse(data) {
      if (data === 'ok') {
        this.addBoard();
        return;
      }

      if (data === 'yes') {
        this.removeBoard();
      }

      this.showPopUp = false;
      this.clearBoard();
      this.setPopUpDefault();
    },
    setPopUpDefault: function setPopUpDefault() {
      this.responses = ['ok', 'cancel'];
      this.defaultResponse = 'cancel';
      this.popUpMessage = '';
      this.popUpHasResponses = true;
      this.showItems = '';
    },
    setLineWidth: function setLineWidth($event) {
      this.lineWidth = $event.target.value;
      this.showPopUp = false;
    },
    clickedButton: function clickedButton(data) {
      if (data === 'add board') {
        this.resize = false;
        this.showBoard();
        return;
      }

      if (data === 'tools') {
        this.showTools();
        return;
      }

      if (data === 'colors') {
        this.showItem = this.showItem.length ? '' : 'colors';
        return;
      }
    },
    editBoard: function editBoard(data) {
      this.board.id = data.id;
      this.board.name = data.name;
      this.showBoard();
    },
    deleteBoard: function deleteBoard(data) {
      this.responses = ['yes', 'no'];
      this.defaultResponse = 'no';
      this.popUpMessage = 'are sure you want to delete this board';
      this.popUpHasResponses = false;
      this.showItems = 'board form';
      this.showPopUp = true;
      this.board.id = data.id;
      this.board.name = data.name;
    },
    removeBoard: function removeBoard() {
      var index = this.getBoardIndex(this.board);

      if (index === -1) {
        return;
      }

      this.boards.splice(index, 1);
      this.adjustHeight();
    },
    adjustHeight: function adjustHeight() {
      if (this.boards.length < 2) {
        this.$el.style.height = "".concat(window.innerHeight, "px");
        return;
      }

      this.$el.style.height = "".concat(window.innerHeight * this.boards.length, "px");
    },
    showBoard: function showBoard() {
      this.popUpHasResponses = true;
      this.showItem = 'board form';
      this.showPopUp = true;
    },
    showTools: function showTools() {
      this.popUpHasResponses = false;
      this.showItem = 'tools';
      this.showPopUp = true;
    },
    afterDangerAlert: function afterDangerAlert(message) {
      if (message === "sorry, a board with name ".concat(this.board.name, " already exists. choose a different name.")) {
        this.showPopUp = true;
      }
    },
    addBoard: function addBoard() {
      if (!this.board.name.length) {
        this.board.name = "board ".concat(this.boards.length + 1);
      }

      if (this.computedBoardNameExists) {
        this.issueDangerAlert({
          message: "sorry, a board with name ".concat(this.board.name, " already exists. choose a different name.")
        });
        return;
      }

      if (!this.board.id) {
        this.board.id = Math.floor(Math.random() * 1000000);
        this.boards.push(_.clone(this.board));
      }

      if (this.board.id) {
        this.updateBoard();
      }

      this.adjustHeight();
      this.clearBoard();
    },
    updateBoard: function updateBoard() {
      var index = this.getBoardIndex(this.board);

      if (index === -1) {
        return;
      }

      this.boards.splice(index, 1, _.clone(this.board));
    },
    getBoardIndex: function getBoardIndex(searchBoard) {
      var by = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'id';
      return this.boards.findIndex(function (board) {
        return board[by] == searchBoard[by];
      });
    },
    clearBoard: function clearBoard() {
      this.board.id = null;
      this.board.name = '';
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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainSelect.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainSelect.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************************************/
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    placeholder: {
      type: String,
      "default": 'select an item'
    },
    hasRadius: {
      type: Boolean,
      "default": false
    },
    value: {
      type: String,
      "default": ''
    },
    backgroundColor: {
      type: String,
      "default": ''
    },
    items: {
      type: Array,
      "default": function _default() {
        return [];
      }
    },
    objects: {
      type: Array,
      "default": function _default() {
        return [];
      }
    }
  },
  data: function data() {
    return {
      activeIcon: 'up',
      inputValue: ''
    };
  },
  watch: {
    inputValue: function inputValue(newValue) {
      if (newValue) {
        if (this.items.length) {
          this.$emit('selection', newValue);
          this.$emit('input', newValue);
        } else {
          var index = this.findObjectIndex(newValue);

          if (index > -1) {
            this.$emit('input', this.objects[index]);
            this.$emit('selection', this.objects[index]);
          }
        }
      } else {
        this.inputValue = '';
      }
    },
    items: {
      immediate: true,
      handler: function handler(newValue) {
        var _this = this;

        if (newValue.findIndex(function (item) {
          return item === _this.inputValue;
        }) === -1) {
          this.inputValue = '';
        }
      }
    },
    value: {
      immediate: true,
      handler: function handler(newValue) {
        if (newValue !== this.inputValue) {
          this.inputValue = newValue;
        }
      }
    }
  },
  mounted: function mounted() {
    if (this.backgroundColor.length) {
      this.$refs.selectedsection.style.backgroundColor = "".concat(this.backgroundColor);
      this.$refs.dropdownsection.style.backgroundColor = "".concat(this.backgroundColor);
    }
  },
  computed: {
    computedItems: function computedItems() {
      return this.items.length ? this.items : this.objects;
    }
  },
  methods: {
    clickedSelect: function clickedSelect() {
      if (this.activeIcon === 'up') {
        this.activeIcon = 'down';
      } else {
        this.activeIcon = 'up';
      }
    },
    clickedItem: function clickedItem(data) {
      if (this.inputValue === data) {
        this.inputValue = '';
      } else this.inputValue = data;

      this.activeIcon = 'up';
    },
    findObjectIndex: function findObjectIndex(name) {
      return this.objects.findIndex(function (object) {
        return object === name || object.name === name;
      });
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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RadioInput.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RadioInput.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************************************/
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    value: {
      type: String,
      "default": ''
    },
    radioValue: {
      type: String,
      "default": ''
    },
    name: {
      type: String,
      "default": ''
    },
    label: {
      type: String,
      "default": ''
    }
  },
  data: function data() {
    return {
      inputRadio: '',
      id: Math.floor(Math.random() * 100)
    };
  },
  watch: {
    inputRadio: function inputRadio(newValue) {
      if (newValue.length) {
        this.$emit('input', newValue);
        this.inputRadio = '';
      }
    },
    value: {
      immediate: true,
      handler: function handler(newValue) {
        if (newValue === this.radioValue && this.$refs.radioinput) {
          this.$refs.radioinput.checked = true;
        } else if (this.$refs.radioinput) {
          this.$refs.radioinput.checked = false;
        }
      }
    }
  },
  computed: {
    computedValue: function computedValue() {
      return this.radioValue && this.radioValue.length ? this.radioValue : this.label;
    }
  },
  methods: {
    inputRadioMethod: function inputRadioMethod() {
      this.inputRadio = this.$refs.radioinput.value; // console.log(this.inputRadio);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/SpecialButton.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/SpecialButton.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************/
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    buttonText: {
      type: String,
      "default": 'click'
    },
    titleText: {
      type: String,
      "default": ''
    }
  },
  computed: {
    computedClasses: function computedClasses() {
      return this.$vnode.data.staticClass ? this.$vnode.data.staticClass : '';
    },
    computedHasFontSizeClasses: function computedHasFontSizeClasses() {
      var _this = this;

      if (!this.computedClasses.length) {
        return false;
      }

      var classes = ['text-xs', 'text-sm', 'text-base'];
      classes.forEach(function (cl) {
        if (_this.computedClasses.includes(cl)) {
          return true;
        }
      });
      return false;
    }
  },
  methods: {
    onClick: function onClick() {
      this.$emit('click');
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/TheBoard.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/TheBoard.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _profile_ProfilePicture__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./profile/ProfilePicture */ "./resources/js/components/profile/ProfilePicture.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    ProfilePicture: _profile_ProfilePicture__WEBPACK_IMPORTED_MODULE_0__.default
  },
  props: {
    board: {
      type: Object,
      "default": function _default() {
        return null;
      }
    },
    activeColor: {
      type: String,
      "default": ''
    },
    resize: {
      type: Boolean,
      "default": false
    },
    what: {
      type: String,
      "default": 'freehand'
    },
    sides: {
      "default": 3
    },
    angle: {
      "default": 0
    },
    lineWidth: {
      "default": 1
    }
  },
  mounted: function mounted() {
    this.initiateCanvas();
    this.$el.addEventListener('resize', this.resizeCanvas());
  },
  beforeDestroy: function beforeDestroy() {
    this.$el.removeEventListener('resize', this.resizeCanvas());
  },
  data: function data() {
    return {
      context: null,
      isDrawing: false,
      backgroundColor: null,
      toggleType: 'on',
      drawingsData: []
    };
  },
  watch: {
    backgroundColor: function backgroundColor(newValue) {
      if (newValue) {
        this.changeBackgroundColor();
      }
    },
    resize: function resize(newValue) {
      if (!newValue) {
        this.resizeCanvas();
        this.clearRectAndPutImageData();
      }
    },
    lineWidth: function lineWidth(newValue) {
      this.stop();
    },
    activeColor: function activeColor(newValue) {
      this.stop();
    },
    sides: function sides(newValue) {
      this.stop();
    },
    angle: function angle(newValue) {
      this.stop();
    },
    what: function what(newValue) {
      this.stop();
    },
    toggleType: function toggleType(newValue) {
      if (newValue) {
        this.toggleBackground();
      }
    }
  },
  methods: {
    editBoard: function editBoard() {
      this.$emit('editBoard', this.board);
    },
    deleteBoard: function deleteBoard() {
      this.$emit('deleteBoard', this.board);
    },
    initiateCanvas: function initiateCanvas() {
      this.resizeCanvas();
      this.context = this.$refs.canvas.getContext('2d');
      this.backgroundColor = 'white';
      this.clearContext();
    },
    resizeCanvas: function resizeCanvas() {
      var height = this.getCanvasParentNodeHeight();
      var width = this.getCanvasParentNodeWidth();
      this.$refs.canvas.style.height = "".concat(height, "px");
      this.$refs.canvas.style.width = "".concat(width, "px");
      this.$refs.canvas.width = width;
      this.$refs.canvas.height = height;
    },
    getCanvasParentNodeHeight: function getCanvasParentNodeHeight() {
      return Number(getComputedStyle(this.$refs.canvas.parentNode).height.replace('px', ''));
    },
    getCanvasParentNodeWidth: function getCanvasParentNodeWidth() {
      var width = Number(getComputedStyle(this.$refs.canvas.parentNode).width.replace('px', ''));
      return width > 675 ? 675 : width;
    },
    start: function start($event) {
      this.isDrawing = true;
      this.context.beginPath();
      this.context.moveTo(this.getClientX($event), this.getClientY($event));
    },
    clearContext: function clearContext() {
      this.drawingsData = [];
      this.clearRect();
    },
    clearRect: function clearRect() {
      this.context.fillStyle = this.backgroundColor;
      this.context.clearRect(0, 0, this.$refs.canvas.width, this.$refs.canvas.height);
      this.context.fillRect(0, 0, this.$refs.canvas.width, this.$refs.canvas.height);
    },
    changeBackgroundColor: function changeBackgroundColor() {
      this.clearRectAndPutImageData();
    },
    clearRectAndPutImageData: function clearRectAndPutImageData() {
      this.clearRect();
      this.putContextImageData();
    },
    draw: function draw($event) {
      if (!this.isDrawing) {
        return;
      }

      this.context.strokeStyle = this.activeColor;
      this.context.lineWidth = Number(this.lineWidth);
      this.context.lineCap = 'round';
      this.context.lineJoin = 'round';
      this.context.lineTo(this.getClientX($event), this.getClientY($event));
      this.context.stroke();
    },
    getClientX: function getClientX($event) {
      return $event.layerX - this.$refs.canvas.offsetLeft;
    },
    getClientY: function getClientY($event) {
      return $event.layerY - this.$refs.canvas.offsetTop;
    },
    stop: function stop() {
      if (!this.isDrawing) {
        return;
      }

      this.context.closePath();
      this.isDrawing = false;
      this.saveData();
    },
    saveData: function saveData() {
      this.drawingsData.push(this.getContextImageData());
    },
    clickedButton: function clickedButton(text) {
      if (text == 'clear') {
        this.clearContext();
        return;
      }

      this.undoDrawing();
    },
    undoDrawing: function undoDrawing() {
      this.drawingsData.pop();

      if (!this.drawingsData.length) {
        this.clearContext();
        return;
      }

      this.putContextImageData();
    },
    getContextImageData: function getContextImageData() {
      return this.context.getImageData(0, 0, this.$refs.canvas.width, this.$refs.canvas.height);
    },
    putContextImageData: function putContextImageData() {
      if (!this.drawingsData.length) {
        return;
      }

      this.context.putImageData(this.drawingsData[this.drawingsData.length - 1], 0, 0);
    },
    toggleBackgroundType: function toggleBackgroundType() {
      this.toggleType = this.toggleType === 'on' ? 'off' : 'on';
    },
    toggleBackground: function toggleBackground() {
      this.backgroundColor = this.backgroundColor === 'white' ? 'black' : 'white';
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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
      showMarkForm: false,
      loading: false,
      mode: ''
    };
  },
  watch: {
    providedMark: {
      immediate: true,
      handler: function handler(newValue) {
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
      this.showMarkForm = false;

      if (this.mode === 'update') {
        this.performActionOnMark({
          type: this.mode,
          newMarkData: data,
          mark: this.answer.mark
        });
        return;
      }

      this.updateMark({
        score: data.score,
        remark: data.remark
      });
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
      this.mode = '';
      var score = 0;

      if (data.type === 'delete') {
        this.performActionOnMark({
          type: data.type,
          mark: data.answer.mark
        });
        return;
      }

      if (data.type === 'update') {
        this.mode = 'update';
        score = this.answer.mark.score;
      }

      if (data.type === 'correct') {
        score = this.answer.question.scoreOver;
      }

      if (data.type === 'partial') {
        score = null;
      }

      this.updateMark({
        score: score
      });
      this.showMarkForm = true;
    },
    performActionOnMark: function performActionOnMark(_ref2) {
      var type = _ref2.type,
          mark = _ref2.mark,
          newMarkData = _ref2.newMarkData;
      this.$emit('performActionOnMark', {
        type: type,
        mark: mark,
        newMarkData: newMarkData
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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
      if (!['update', 'delete'].includes(data.type)) {
        data.assessmentSectionId = this.assessmentSection.id;
      }

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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMiniBadge.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMiniBadge.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _dashboard_AssessmentSectionMarkingBadge_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../dashboard/AssessmentSectionMarkingBadge.vue */ "./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue");
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


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    AssessmentSectionMarkingBadge: _dashboard_AssessmentSectionMarkingBadge_vue__WEBPACK_IMPORTED_MODULE_0__.default,
    NavigationComponent: _NavigationComponent_vue__WEBPACK_IMPORTED_MODULE_1__.default
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
    computedNotAddedby: function computedNotAddedby() {
      var _this$work, _this$getUser;

      if (((_this$work = this.work) === null || _this$work === void 0 ? void 0 : _this$work.addedby.userId) == ((_this$getUser = this.getUser) === null || _this$getUser === void 0 ? void 0 : _this$getUser.id)) {
        return true;
      }

      return false;
    },
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
    },
    computedPossiblyDone: function computedPossiblyDone() {
      return this.work.answers.filter(function (answer) {
        return answer.mark && answer.isMarker;
      }).length === this.work.answers.length;
    }
  },
  methods: {
    initiate: function initiate() {
      this.firstAssessmentSectionId = this.assessment.assessmentSections[0].id;
      this.lastAssessmentSectionId = this.assessment.assessmentSections[this.assessment.assessmentSections.length - 1].id;
      this.clickedSectionNavigator('next');
    },
    clickedSectionNavigator: function clickedSectionNavigator(text) {
      if (text === 'done') {
        this.$emit('goToStep', 3);
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
    marked: function marked(data) {
      this.$emit('marked', data);
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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/ResizingComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/ResizingComponent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************************************************/
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    hasNegativeTop: {
      type: Boolean,
      "default": false
    },
    resize: {
      type: Boolean,
      "default": true
    },
    move: {
      type: Boolean,
      "default": false
    },
    minWidth: {
      type: Number,
      "default": 10
    },
    minHeight: {
      type: Number,
      "default": 10
    },
    maxWidth: {
      type: Number,
      "default": null
    },
    maxHeight: {
      type: Number,
      "default": null
    },
    maxRight: {
      type: Number,
      "default": null
    },
    maxBottom: {
      type: Number,
      "default": null
    }
  },
  data: function data() {
    return {
      isResizing: false,
      isMoving: false,
      sizeType: null,
      originalHeight: 0,
      originalWidth: 0,
      originalMouseX: 0,
      originalMouseY: 0,
      originalBoundingClientRectLeft: 0,
      originalBoundingClientRectTop: 0
    };
  },
  watch: {
    isResizing: function isResizing(newValue, oldValue) {
      if (newValue) {
        this.$emit('startedResizing', this.$slots["default"][0]);
      }

      if (!newValue) {
        this.$emit('stoppedResizing', this.$slots["default"][0]);
      }
    },
    isMoving: function isMoving(newValue, oldValue) {
      if (newValue) {
        this.$emit('startedMoving', this.$slots["default"][0]);
      }

      if (!newValue) {
        this.$emit('stoppedMoving', this.$slots["default"][0]);
      }
    }
  },
  methods: {
    startMoving: function startMoving($event) {
      if (this.isResizing || !this.move) {
        return;
      }

      this.isMoving = true;
      this.originalBoundingClientRectLeft = this.convertElementPropertyFromPixelsToNumber(this.$el, 'left');
      this.originalBoundingClientRectTop = this.convertElementPropertyFromPixelsToNumber(this.$el, 'top');
      this.originalMouseX = $event.pageX;
      this.originalMouseY = $event.pageY;
      window.addEventListener('mousemove', this.continueMoving);
      window.addEventListener('mouseup', this.stopMoving);
    },
    continueMoving: function continueMoving($event) {
      this.setElementsStyleProperty('left', "".concat($event.pageX - this.originalMouseX + this.originalBoundingClientRectLeft, "px"));
      this.setElementsStyleProperty('top', "".concat($event.pageY - this.originalMouseY + this.originalBoundingClientRectTop, "px"));
    },
    stopMoving: function stopMoving() {
      this.isMoving = false;
      window.removeEventListener('mousemove', this.continueMoving);
      window.removeEventListener('mouseup', this.stopMoving);
    },
    startResize: function startResize($event) {
      if (this.isMoving) {
        return;
      }

      this.isResizing = true;
      this.originalHeight = this.convertElementPropertyFromPixelsToNumber(this.$slots["default"][0].elm, 'height');
      this.originalWidth = this.convertElementPropertyFromPixelsToNumber(this.$slots["default"][0].elm, 'width');
      this.originalBoundingClientRectLeft = this.$el.offsetLeft;
      this.originalBoundingClientRectTop = this.convertElementPropertyFromPixelsToNumber(this.$el, 'top');
      this.originalMouseX = $event.pageX;
      this.originalMouseY = $event.pageY;

      if ($event.target.dataset.button) {
        this.sizeType = $event.target.dataset.button;
      }

      window.addEventListener('mousemove', this.continueResizing);
      window.addEventListener('mouseup', this.stopResizing);
    },
    continueResizing: function continueResizing($event) {
      if (!this.isResizing) {
        return;
      }

      this.changeSize($event);
    },
    changeSize: function changeSize($event) {
      if (this.sizeType === 'top-left') {
        this.toTopLeft($event);
        return;
      }

      if (this.sizeType === 'top-right') {
        this.toTopRight($event);
        return;
      }

      if (this.sizeType === 'bottom-left') {
        this.toBottomLeft($event);
        return;
      }

      this.toBottomRight($event);
    },
    stopResizing: function stopResizing() {
      this.isResizing = false;
      window.removeEventListener('mousemove', this.continueResizing);
      window.removeEventListener('mouseup', this.stopResizing);
    },
    adjustHeight: function adjustHeight(height) {
      if (this.maxBottom && this.originalBoundingClientRectTop + height > this.maxBottom) {
        return this.maxBottom - this.originalBoundingClientRectTop;
      }

      return height;
    },
    convertElementPropertyFromPixelsToNumber: function convertElementPropertyFromPixelsToNumber(el, property) {
      var value = Number(getComputedStyle(el)[property].replace('px', ''));
      return isNaN(value) ? 0 : value;
    },
    toBottomLeft: function toBottomLeft($event) {
      var addedWidth = $event.pageX - this.originalMouseX,
          addedHeight = $event.pageY - this.originalMouseY;
      this.updateElementAndSlotSize({
        width: this.originalWidth - addedWidth,
        height: this.adjustHeight(this.originalHeight + addedHeight),
        addedWidth: addedWidth
      });
    },
    toBottomRight: function toBottomRight($event) {
      var addedWidth = $event.pageX - this.originalMouseX,
          addedHeight = $event.pageY - this.originalMouseY;
      this.updateElementAndSlotSize({
        width: this.originalWidth + addedWidth,
        height: this.adjustHeight(this.originalHeight + addedHeight)
      });
    },
    toTopLeft: function toTopLeft($event) {
      var addedWidth = $event.pageX - this.originalMouseX;
      var addedHeight = $event.pageY - this.originalMouseY;
      this.updateElementAndSlotSize({
        width: this.originalWidth - addedWidth,
        addedWidth: addedWidth,
        height: this.adjustHeight(this.originalHeight - addedHeight),
        addedHeight: addedHeight
      });
    },
    toTopRight: function toTopRight($event) {
      var addedHeight = $event.pageY - this.originalMouseY;
      this.updateElementAndSlotSize({
        width: this.originalWidth + ($event.pageX - this.originalMouseX),
        addedHeight: addedHeight,
        height: this.adjustHeight(this.originalHeight - addedHeight)
      });
    },
    updateElementAndSlotSize: function updateElementAndSlotSize(_ref) {
      var width = _ref.width,
          addedWidth = _ref.addedWidth,
          height = _ref.height,
          addedHeight = _ref.addedHeight;

      if (width >= this.minWidth && (!this.maxWidth || width <= this.maxWidth)) {
        this.setElementsStyleProperty('width', "".concat(width, "px"));

        if (addedWidth && this.originalBoundingClientRectLeft + addedWidth >= 0) {
          this.setElementsStyleProperty('left', "".concat(this.originalBoundingClientRectLeft + addedWidth, "px"));
        }
      }

      if (height >= this.minHeight && (!this.maxHeight || height <= this.maxHeight)) {
        this.setElementsStyleProperty('height', "".concat(height, "px"));

        if (addedHeight && (this.hasNegativeTop || this.originalBoundingClientRectTop + addedHeight >= 0)) {
          this.setElementsStyleProperty('top', "".concat(this.originalBoundingClientRectTop + addedHeight, "px"));
        }
      }
    },
    setElementsStyleProperty: function setElementsStyleProperty(property, value) {
      this.$el.style[property] = this.$slots["default"][0].elm.style[property] = value;
    },
    stop: function stop() {
      this.$emit('stop');
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Testing.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Testing.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _components_forms_WorkAnsweringForm__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/forms/WorkAnsweringForm */ "./resources/js/components/forms/WorkAnsweringForm.vue");
/* harmony import */ var _components_AssessmentSingle__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/AssessmentSingle */ "./resources/js/components/AssessmentSingle.vue");
/* harmony import */ var _components_LessonBoard_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/LessonBoard.vue */ "./resources/js/components/LessonBoard.vue");
/* harmony import */ var _components_specials_ResizingComponent_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../components/specials/ResizingComponent.vue */ "./resources/js/components/specials/ResizingComponent.vue");
//
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
      resize: true,
      value: ''
    };
  },
  components: {
    WorkAnsweringForm: _components_forms_WorkAnsweringForm__WEBPACK_IMPORTED_MODULE_0__.default,
    AssessmentSingle: _components_AssessmentSingle__WEBPACK_IMPORTED_MODULE_1__.default,
    ResizingComponent: _components_specials_ResizingComponent_vue__WEBPACK_IMPORTED_MODULE_3__.default,
    LessonBoard: _components_LessonBoard_vue__WEBPACK_IMPORTED_MODULE_2__.default
  },
  methods: {
    clickedTesting: function clickedTesting() {
      this.show = true;
    }
  }
});

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

/***/ "./resources/js/mixins/Storage.mixin.js":
/*!**********************************************!*\
  !*** ./resources/js/mixins/Storage.mixin.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  methods: {
    setItem: function setItem(key, items) {
      window.YoureduStorage.set(key, items);
    },
    getItem: function getItem(key) {
      return window.YoureduStorage.get(key);
    },
    removeItem: function removeItem(key) {
      return window.YoureduStorage.remove(key);
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

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/GreyButton.vue?vue&type=style&index=0&id=d0d2f1c8&lang=scss&scoped=true&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/GreyButton.vue?vue&type=style&index=0&id=d0d2f1c8&lang=scss&scoped=true& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
___CSS_LOADER_EXPORT___.push([module.id, ".small-msg[data-v-d0d2f1c8] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.grey-button-wrapper[data-v-d0d2f1c8] {\n  padding: 5px;\n  font-size: 14px;\n  cursor: pointer;\n  width: -webkit-fit-content;\n  width: -moz-fit-content;\n  width: fit-content;\n  min-width: 35px;\n  color: white;\n  background-color: gray;\n  border-radius: 10px;\n  text-align: center;\n}\n.grey-button-wrapper[data-v-d0d2f1c8]:hover {\n  transition: all 1s ease;\n  background-color: green;\n  box-shadow: 0 0 3px gray;\n}\n.active[data-v-d0d2f1c8] {\n  transition: all 1s ease;\n  background-color: green;\n  box-shadow: 0 0 3px gray;\n}\n@media screen and (max-width: 800px) {\n.grey-button-wrapper[data-v-d0d2f1c8] {\n    font-size: 12px;\n}\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemRequestSection.vue?vue&type=style&index=0&id=159bfc74&lang=scss&scoped=true&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemRequestSection.vue?vue&type=style&index=0&id=159bfc74&lang=scss&scoped=true& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
___CSS_LOADER_EXPORT___.push([module.id, ".small-msg[data-v-159bfc74] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.item-request-section[data-v-159bfc74] {\n  position: absolute;\n  left: 0;\n  top: 0;\n  width: 100%;\n  height: 100%;\n  background: mintcream;\n  border-radius: 10px;\n  box-shadow: 0 0 2px grey;\n}\n.item-request-section .close[data-v-159bfc74],\n.item-request-section .pencil[data-v-159bfc74] {\n  color: gray;\n  position: absolute;\n  right: 10px;\n  top: 10px;\n  padding: 5px;\n  font-size: 14px;\n  cursor: pointer;\n}\n.item-request-section .close[data-v-159bfc74]:hover,\n.item-request-section .pencil[data-v-159bfc74]:hover {\n  color: red;\n  transition: all 1s ease-in-out;\n}\n.item-request-section .pencil[data-v-159bfc74] {\n  right: 40px;\n  top: 5px;\n}\n.item-request-section .pencil[data-v-159bfc74]:hover {\n  color: green;\n}\n.item-request-section .title[data-v-159bfc74] {\n  width: 100%;\n  text-align: center;\n  margin: 20px 0 0;\n  color: gray;\n  text-transform: capitalize;\n}\n.item-request-section .body[data-v-159bfc74] {\n  padding: 10px;\n  height: 88%;\n}\n.item-request-section .body .search-section[data-v-159bfc74] {\n  margin-bottom: 10px;\n}\n.item-request-section .body .search-types[data-v-159bfc74] {\n  width: 100%;\n  display: inline-flex;\n  justify-content: space-around;\n  align-items: center;\n  flex-wrap: wrap;\n}\n.item-request-section .body .search-types .grey-button[data-v-159bfc74] {\n  margin-bottom: 10px;\n}\n.item-request-section .body .accounts-section[data-v-159bfc74] {\n  padding: 10px;\n  overflow-y: auto;\n  max-height: 400px;\n  width: 100%;\n  margin-top: 20px;\n}\n.item-request-section .body .accounts-section .no-participants[data-v-159bfc74] {\n  width: 100%;\n  height: 100px;\n  display: flex;\n  justify-content: center;\n  align-items: center;\n  color: gray;\n  font-size: 14px;\n}\n.item-request-section .body .accounts-section .participant-badge[data-v-159bfc74] {\n  margin-bottom: 10px;\n}\n.item-request-section .body .accounts-section .loading[data-v-159bfc74] {\n  width: 100%;\n  text-align: center;\n}\n.item-request-section .body .accounts-section .show-more[data-v-159bfc74] {\n  padding: 5px;\n  border-radius: 10px;\n  margin: 5px auto;\n  width: -webkit-fit-content;\n  width: -moz-fit-content;\n  width: fit-content;\n  background: white;\n  font-size: 11px;\n  box-shadow: 0 0 2px green;\n  cursor: pointer;\n  color: green;\n  font-weight: 600;\n}", ""]);
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

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainSelect.vue?vue&type=style&index=0&id=4cf5d240&lang=scss&scoped=true&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainSelect.vue?vue&type=style&index=0&id=4cf5d240&lang=scss&scoped=true& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
___CSS_LOADER_EXPORT___.push([module.id, ".small-msg[data-v-4cf5d240] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.main-select-wrapper[data-v-4cf5d240] {\n  position: relative;\n}\n.main-select-wrapper .selected-section[data-v-4cf5d240] {\n  padding: 10px;\n  border-bottom: 2px solid #16e9cd;\n  position: relative;\n  color: gray;\n  cursor: pointer;\n}\n.main-select-wrapper .selected-section .icon[data-v-4cf5d240] {\n  position: absolute;\n  top: 5px;\n  right: 5px;\n  cursor: pointer;\n  padding: 5px;\n  color: gray;\n}\n.main-select-wrapper .has-radius[data-v-4cf5d240] {\n  border-radius: 10px;\n}\n.main-select-wrapper .selected-section.active[data-v-4cf5d240] {\n  color: black;\n}\n.main-select-wrapper .dropdown-section[data-v-4cf5d240] {\n  border: 1px solid #16e9cd;\n  width: 90%;\n  margin: 0 auto;\n  border-radius: 5px;\n  height: -webkit-fit-content;\n  height: -moz-fit-content;\n  height: fit-content;\n  max-height: 0;\n  overflow-y: hidden;\n  opacity: 0;\n  position: absolute;\n}\n.main-select-wrapper .dropdown-section .dropdown-item[data-v-4cf5d240] {\n  font-size: 16px;\n  color: gray;\n  cursor: pointer;\n}\n.main-select-wrapper .dropdown-section .dropdown-item[data-v-4cf5d240]:hover {\n  background-color: mintcream;\n}\n.main-select-wrapper .dropdown-section .dropdown-item.active[data-v-4cf5d240] {\n  background-color: mintcream;\n  color: black;\n}\n.main-select-wrapper .dropdown-section.active[data-v-4cf5d240] {\n  transition: all 0.4s ease;\n  max-height: 200px;\n  overflow-y: auto;\n  margin-top: 10px;\n  opacity: 1;\n  z-index: 1;\n}\n@media screen and (max-width: 800px) {\n.main-select-wrapper .selected-section[data-v-4cf5d240] {\n    font-size: 14px;\n}\n.main-select-wrapper .dropdown-section .dropdown-item[data-v-4cf5d240] {\n    font-size: 14px;\n}\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RadioInput.vue?vue&type=style&index=0&id=5c4565ba&lang=scss&scoped=true&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RadioInput.vue?vue&type=style&index=0&id=5c4565ba&lang=scss&scoped=true& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
___CSS_LOADER_EXPORT___.push([module.id, ".small-msg[data-v-5c4565ba] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.check-input-wrapper[data-v-5c4565ba] {\n  position: relative;\n}\n.check-input-wrapper input[type=radio][data-v-5c4565ba] {\n  display: none;\n}\n.check-input-wrapper label[data-v-5c4565ba] {\n  color: gray;\n  font-weight: normal;\n  font-size: 12px;\n  margin: 5px;\n  cursor: pointer;\n}\n.check-input-wrapper label[data-v-5c4565ba]::before {\n  content: \"\";\n  position: relative;\n  top: 3px;\n  margin: 0 5px 0 0;\n  display: inline-flex;\n  width: 20px;\n  height: 20px;\n  border-radius: 11px;\n  border: 2px solid gray;\n  background-color: transparent;\n}\n.check-input-wrapper input[type=radio]:checked + label[data-v-5c4565ba]::after {\n  border-radius: 11px;\n  width: 10px;\n  height: 10px;\n  position: absolute;\n  top: 8px;\n  left: 10px;\n  content: \"\";\n  display: block;\n  background: #16e9cd;\n}\n.check-input-wrapper input[type=radio]:checked + label[data-v-5c4565ba],\n.check-input-wrapper .checked.label[data-v-5c4565ba] {\n  color: #16e9cd;\n}\n.check-input-wrapper input[type=radio]:checked + label[data-v-5c4565ba]::before {\n  border-color: #16e9cd;\n}\n.checked label[data-v-5c4565ba] {\n  color: #16e9cd;\n}\n.checked label[data-v-5c4565ba]::before {\n  border-color: #16e9cd;\n}\n.checked label[data-v-5c4565ba]::after {\n  border-radius: 11px;\n  width: 10px;\n  height: 10px;\n  position: absolute;\n  top: 8px;\n  left: 10px;\n  content: \"\";\n  display: block;\n  background: #16e9cd;\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/SpecialButton.vue?vue&type=style&index=0&id=784cb920&lang=scss&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/SpecialButton.vue?vue&type=style&index=0&id=784cb920&lang=scss&scoped=true& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
___CSS_LOADER_EXPORT___.push([module.id, ".small-msg[data-v-784cb920] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\nbutton[data-v-784cb920] {\n  border: 1px solid rebeccapurple;\n  box-shadow: -0.5px -0.5px 0.5px rebeccapurple;\n  border-radius: 5px;\n  background-color: azure;\n}\nbutton[data-v-784cb920]:hover {\n  box-shadow: 1px 1px 1px rebeccapurple, -1px -1px 1px rebeccapurple;\n  transition: all 0.5s ease;\n}\n.btn-size[data-v-784cb920] {\n  font-size: 16px;\n}\n@media screen and (max-width: 800px) {\n.btn-size[data-v-784cb920] {\n    font-size: 13px;\n}\n}", ""]);
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

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true& ***!
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
___CSS_LOADER_EXPORT___.push([module.id, ".small-msg[data-v-3697b187] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.question-badge-wrapper[data-v-3697b187] {\n  min-width: 100%;\n  display: flex;\n  width: 100%;\n  align-items: center;\n  justify-content: center;\n}\n.question-badge-wrapper .other[data-v-3697b187] {\n  position: absolute;\n  right: 10px;\n  top: 5px;\n}\n.question-badge-wrapper .other .drag[data-v-3697b187] {\n  cursor: -webkit-grab;\n  cursor: grab;\n  font-size: 20px;\n  color: gray;\n}\n.question-badge-wrapper .other .close[data-v-3697b187] {\n  font-size: 30px;\n  color: gray;\n  padding: 5px;\n  cursor: pointer;\n}\n.question-badge-wrapper .other .close[data-v-3697b187]:hover {\n  color: red;\n}\n.question-badge-wrapper .main[data-v-3697b187] {\n  padding: 10px;\n  border-radius: 10px;\n  cursor: pointer;\n  width: 100%;\n}\n.question-badge-wrapper .main .body[data-v-3697b187] {\n  font-size: 14px;\n  color: black;\n}\n.question-badge-wrapper .main .file[data-v-3697b187] {\n  margin: 0 0 10px;\n}\n.question-badge-wrapper .main .hint[data-v-3697b187] {\n  font-size: 12px;\n  color: gray;\n  width: 100%;\n  text-align: center;\n  margin: 5px;\n}\n.question-badge-wrapper .main .score[data-v-3697b187] {\n  font-size: 12px;\n  color: gray;\n  width: 100%;\n  text-align: end;\n}", ""]);
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

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Testing.vue?vue&type=style&index=0&id=14793e41&lang=scss&scoped=true&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Testing.vue?vue&type=style&index=0&id=14793e41&lang=scss&scoped=true& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
___CSS_LOADER_EXPORT___.push([module.id, ".small-msg[data-v-14793e41] {\n  font-size: 12px;\n  color: gray;\n  text-align: center;\n  width: 100%;\n}\n.testing[data-v-14793e41] {\n  width: 100%;\n  height: 100vh;\n}", ""]);
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

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/GreyButton.vue?vue&type=style&index=0&id=d0d2f1c8&lang=scss&scoped=true&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/GreyButton.vue?vue&type=style&index=0&id=d0d2f1c8&lang=scss&scoped=true& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_GreyButton_vue_vue_type_style_index_0_id_d0d2f1c8_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./GreyButton.vue?vue&type=style&index=0&id=d0d2f1c8&lang=scss&scoped=true& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/GreyButton.vue?vue&type=style&index=0&id=d0d2f1c8&lang=scss&scoped=true&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_GreyButton_vue_vue_type_style_index_0_id_d0d2f1c8_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default, options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_GreyButton_vue_vue_type_style_index_0_id_d0d2f1c8_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default.locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemRequestSection.vue?vue&type=style&index=0&id=159bfc74&lang=scss&scoped=true&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemRequestSection.vue?vue&type=style&index=0&id=159bfc74&lang=scss&scoped=true& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemRequestSection_vue_vue_type_style_index_0_id_159bfc74_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ItemRequestSection.vue?vue&type=style&index=0&id=159bfc74&lang=scss&scoped=true& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemRequestSection.vue?vue&type=style&index=0&id=159bfc74&lang=scss&scoped=true&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemRequestSection_vue_vue_type_style_index_0_id_159bfc74_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default, options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemRequestSection_vue_vue_type_style_index_0_id_159bfc74_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default.locals || {});

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

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainSelect.vue?vue&type=style&index=0&id=4cf5d240&lang=scss&scoped=true&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainSelect.vue?vue&type=style&index=0&id=4cf5d240&lang=scss&scoped=true& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_MainSelect_vue_vue_type_style_index_0_id_4cf5d240_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MainSelect.vue?vue&type=style&index=0&id=4cf5d240&lang=scss&scoped=true& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainSelect.vue?vue&type=style&index=0&id=4cf5d240&lang=scss&scoped=true&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_MainSelect_vue_vue_type_style_index_0_id_4cf5d240_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default, options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_MainSelect_vue_vue_type_style_index_0_id_4cf5d240_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default.locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RadioInput.vue?vue&type=style&index=0&id=5c4565ba&lang=scss&scoped=true&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RadioInput.vue?vue&type=style&index=0&id=5c4565ba&lang=scss&scoped=true& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_RadioInput_vue_vue_type_style_index_0_id_5c4565ba_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./RadioInput.vue?vue&type=style&index=0&id=5c4565ba&lang=scss&scoped=true& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RadioInput.vue?vue&type=style&index=0&id=5c4565ba&lang=scss&scoped=true&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_RadioInput_vue_vue_type_style_index_0_id_5c4565ba_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default, options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_RadioInput_vue_vue_type_style_index_0_id_5c4565ba_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default.locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/SpecialButton.vue?vue&type=style&index=0&id=784cb920&lang=scss&scoped=true&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/SpecialButton.vue?vue&type=style&index=0&id=784cb920&lang=scss&scoped=true& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_SpecialButton_vue_vue_type_style_index_0_id_784cb920_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SpecialButton.vue?vue&type=style&index=0&id=784cb920&lang=scss&scoped=true& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/SpecialButton.vue?vue&type=style&index=0&id=784cb920&lang=scss&scoped=true&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_SpecialButton_vue_vue_type_style_index_0_id_784cb920_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default, options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_SpecialButton_vue_vue_type_style_index_0_id_784cb920_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default.locals || {});

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

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_style_index_0_id_3697b187_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_style_index_0_id_3697b187_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default, options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_style_index_0_id_3697b187_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default.locals || {});

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

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Testing.vue?vue&type=style&index=0&id=14793e41&lang=scss&scoped=true&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Testing.vue?vue&type=style&index=0&id=14793e41&lang=scss&scoped=true& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_Testing_vue_vue_type_style_index_0_id_14793e41_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Testing.vue?vue&type=style&index=0&id=14793e41&lang=scss&scoped=true& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Testing.vue?vue&type=style&index=0&id=14793e41&lang=scss&scoped=true&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_Testing_vue_vue_type_style_index_0_id_14793e41_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default, options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_Testing_vue_vue_type_style_index_0_id_14793e41_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__.default.locals || {});

/***/ }),

/***/ "./resources/js/components/AssessmentDetails.vue":
/*!*******************************************************!*\
  !*** ./resources/js/components/AssessmentDetails.vue ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _AssessmentDetails_vue_vue_type_template_id_7b16d245_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AssessmentDetails.vue?vue&type=template&id=7b16d245&scoped=true& */ "./resources/js/components/AssessmentDetails.vue?vue&type=template&id=7b16d245&scoped=true&");
/* harmony import */ var _AssessmentDetails_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AssessmentDetails.vue?vue&type=script&lang=js& */ "./resources/js/components/AssessmentDetails.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _AssessmentDetails_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _AssessmentDetails_vue_vue_type_template_id_7b16d245_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _AssessmentDetails_vue_vue_type_template_id_7b16d245_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "7b16d245",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/AssessmentDetails.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/AssessmentSingle.vue":
/*!******************************************************!*\
  !*** ./resources/js/components/AssessmentSingle.vue ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _AssessmentSingle_vue_vue_type_template_id_181a89d6_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AssessmentSingle.vue?vue&type=template&id=181a89d6&scoped=true& */ "./resources/js/components/AssessmentSingle.vue?vue&type=template&id=181a89d6&scoped=true&");
/* harmony import */ var _AssessmentSingle_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AssessmentSingle.vue?vue&type=script&lang=js& */ "./resources/js/components/AssessmentSingle.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _AssessmentSingle_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _AssessmentSingle_vue_vue_type_template_id_181a89d6_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _AssessmentSingle_vue_vue_type_template_id_181a89d6_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "181a89d6",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/AssessmentSingle.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

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

/***/ "./resources/js/components/GreyButton.vue":
/*!************************************************!*\
  !*** ./resources/js/components/GreyButton.vue ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _GreyButton_vue_vue_type_template_id_d0d2f1c8_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./GreyButton.vue?vue&type=template&id=d0d2f1c8&scoped=true& */ "./resources/js/components/GreyButton.vue?vue&type=template&id=d0d2f1c8&scoped=true&");
/* harmony import */ var _GreyButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./GreyButton.vue?vue&type=script&lang=js& */ "./resources/js/components/GreyButton.vue?vue&type=script&lang=js&");
/* harmony import */ var _GreyButton_vue_vue_type_style_index_0_id_d0d2f1c8_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./GreyButton.vue?vue&type=style&index=0&id=d0d2f1c8&lang=scss&scoped=true& */ "./resources/js/components/GreyButton.vue?vue&type=style&index=0&id=d0d2f1c8&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__.default)(
  _GreyButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _GreyButton_vue_vue_type_template_id_d0d2f1c8_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _GreyButton_vue_vue_type_template_id_d0d2f1c8_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "d0d2f1c8",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/GreyButton.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/ItemRequestSection.vue":
/*!********************************************************!*\
  !*** ./resources/js/components/ItemRequestSection.vue ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ItemRequestSection_vue_vue_type_template_id_159bfc74_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ItemRequestSection.vue?vue&type=template&id=159bfc74&scoped=true& */ "./resources/js/components/ItemRequestSection.vue?vue&type=template&id=159bfc74&scoped=true&");
/* harmony import */ var _ItemRequestSection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ItemRequestSection.vue?vue&type=script&lang=js& */ "./resources/js/components/ItemRequestSection.vue?vue&type=script&lang=js&");
/* harmony import */ var _ItemRequestSection_vue_vue_type_style_index_0_id_159bfc74_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ItemRequestSection.vue?vue&type=style&index=0&id=159bfc74&lang=scss&scoped=true& */ "./resources/js/components/ItemRequestSection.vue?vue&type=style&index=0&id=159bfc74&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__.default)(
  _ItemRequestSection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _ItemRequestSection_vue_vue_type_template_id_159bfc74_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _ItemRequestSection_vue_vue_type_template_id_159bfc74_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "159bfc74",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/ItemRequestSection.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/ItemViewCover.vue":
/*!***************************************************!*\
  !*** ./resources/js/components/ItemViewCover.vue ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ItemViewCover_vue_vue_type_template_id_5d205664_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ItemViewCover.vue?vue&type=template&id=5d205664&scoped=true& */ "./resources/js/components/ItemViewCover.vue?vue&type=template&id=5d205664&scoped=true&");
/* harmony import */ var _ItemViewCover_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ItemViewCover.vue?vue&type=script&lang=js& */ "./resources/js/components/ItemViewCover.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _ItemViewCover_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _ItemViewCover_vue_vue_type_template_id_5d205664_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _ItemViewCover_vue_vue_type_template_id_5d205664_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "5d205664",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/ItemViewCover.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/LessonBoard.vue":
/*!*************************************************!*\
  !*** ./resources/js/components/LessonBoard.vue ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _LessonBoard_vue_vue_type_template_id_27c2d873_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LessonBoard.vue?vue&type=template&id=27c2d873&scoped=true& */ "./resources/js/components/LessonBoard.vue?vue&type=template&id=27c2d873&scoped=true&");
/* harmony import */ var _LessonBoard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LessonBoard.vue?vue&type=script&lang=js& */ "./resources/js/components/LessonBoard.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _LessonBoard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _LessonBoard_vue_vue_type_template_id_27c2d873_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _LessonBoard_vue_vue_type_template_id_27c2d873_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "27c2d873",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/LessonBoard.vue"
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

/***/ "./resources/js/components/MainSelect.vue":
/*!************************************************!*\
  !*** ./resources/js/components/MainSelect.vue ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _MainSelect_vue_vue_type_template_id_4cf5d240_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MainSelect.vue?vue&type=template&id=4cf5d240&scoped=true& */ "./resources/js/components/MainSelect.vue?vue&type=template&id=4cf5d240&scoped=true&");
/* harmony import */ var _MainSelect_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MainSelect.vue?vue&type=script&lang=js& */ "./resources/js/components/MainSelect.vue?vue&type=script&lang=js&");
/* harmony import */ var _MainSelect_vue_vue_type_style_index_0_id_4cf5d240_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./MainSelect.vue?vue&type=style&index=0&id=4cf5d240&lang=scss&scoped=true& */ "./resources/js/components/MainSelect.vue?vue&type=style&index=0&id=4cf5d240&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__.default)(
  _MainSelect_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _MainSelect_vue_vue_type_template_id_4cf5d240_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _MainSelect_vue_vue_type_template_id_4cf5d240_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "4cf5d240",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/MainSelect.vue"
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

/***/ "./resources/js/components/RadioInput.vue":
/*!************************************************!*\
  !*** ./resources/js/components/RadioInput.vue ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _RadioInput_vue_vue_type_template_id_5c4565ba_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./RadioInput.vue?vue&type=template&id=5c4565ba&scoped=true& */ "./resources/js/components/RadioInput.vue?vue&type=template&id=5c4565ba&scoped=true&");
/* harmony import */ var _RadioInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./RadioInput.vue?vue&type=script&lang=js& */ "./resources/js/components/RadioInput.vue?vue&type=script&lang=js&");
/* harmony import */ var _RadioInput_vue_vue_type_style_index_0_id_5c4565ba_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./RadioInput.vue?vue&type=style&index=0&id=5c4565ba&lang=scss&scoped=true& */ "./resources/js/components/RadioInput.vue?vue&type=style&index=0&id=5c4565ba&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__.default)(
  _RadioInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _RadioInput_vue_vue_type_template_id_5c4565ba_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _RadioInput_vue_vue_type_template_id_5c4565ba_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "5c4565ba",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/RadioInput.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/SpecialButton.vue":
/*!***************************************************!*\
  !*** ./resources/js/components/SpecialButton.vue ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _SpecialButton_vue_vue_type_template_id_784cb920_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SpecialButton.vue?vue&type=template&id=784cb920&scoped=true& */ "./resources/js/components/SpecialButton.vue?vue&type=template&id=784cb920&scoped=true&");
/* harmony import */ var _SpecialButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SpecialButton.vue?vue&type=script&lang=js& */ "./resources/js/components/SpecialButton.vue?vue&type=script&lang=js&");
/* harmony import */ var _SpecialButton_vue_vue_type_style_index_0_id_784cb920_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./SpecialButton.vue?vue&type=style&index=0&id=784cb920&lang=scss&scoped=true& */ "./resources/js/components/SpecialButton.vue?vue&type=style&index=0&id=784cb920&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__.default)(
  _SpecialButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _SpecialButton_vue_vue_type_template_id_784cb920_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _SpecialButton_vue_vue_type_template_id_784cb920_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "784cb920",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/SpecialButton.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/TheBoard.vue":
/*!**********************************************!*\
  !*** ./resources/js/components/TheBoard.vue ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _TheBoard_vue_vue_type_template_id_e7752040_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./TheBoard.vue?vue&type=template&id=e7752040&scoped=true& */ "./resources/js/components/TheBoard.vue?vue&type=template&id=e7752040&scoped=true&");
/* harmony import */ var _TheBoard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TheBoard.vue?vue&type=script&lang=js& */ "./resources/js/components/TheBoard.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _TheBoard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _TheBoard_vue_vue_type_template_id_e7752040_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _TheBoard_vue_vue_type_template_id_e7752040_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "e7752040",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/TheBoard.vue"
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

/***/ "./resources/js/components/dashboard/AnswerMarkingBadge.vue":
/*!******************************************************************!*\
  !*** ./resources/js/components/dashboard/AnswerMarkingBadge.vue ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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

/***/ "./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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

/***/ "./resources/js/components/dashboard/AssessmentSectionMiniBadge.vue":
/*!**************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionMiniBadge.vue ***!
  \**************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _AssessmentSectionMiniBadge_vue_vue_type_template_id_29cbdc4f_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AssessmentSectionMiniBadge.vue?vue&type=template&id=29cbdc4f&scoped=true& */ "./resources/js/components/dashboard/AssessmentSectionMiniBadge.vue?vue&type=template&id=29cbdc4f&scoped=true&");
/* harmony import */ var _AssessmentSectionMiniBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AssessmentSectionMiniBadge.vue?vue&type=script&lang=js& */ "./resources/js/components/dashboard/AssessmentSectionMiniBadge.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _AssessmentSectionMiniBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _AssessmentSectionMiniBadge_vue_vue_type_template_id_29cbdc4f_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _AssessmentSectionMiniBadge_vue_vue_type_template_id_29cbdc4f_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "29cbdc4f",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/dashboard/AssessmentSectionMiniBadge.vue"
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

/***/ "./resources/js/components/dashboard/QuestionBadge.vue":
/*!*************************************************************!*\
  !*** ./resources/js/components/dashboard/QuestionBadge.vue ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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

/***/ "./resources/js/components/forms/AssessmentSectionMarkingForm.vue":
/*!************************************************************************!*\
  !*** ./resources/js/components/forms/AssessmentSectionMarkingForm.vue ***!
  \************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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

/***/ "./resources/js/components/specials/ResizingComponent.vue":
/*!****************************************************************!*\
  !*** ./resources/js/components/specials/ResizingComponent.vue ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ResizingComponent_vue_vue_type_template_id_10bbae82_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ResizingComponent.vue?vue&type=template&id=10bbae82&scoped=true& */ "./resources/js/components/specials/ResizingComponent.vue?vue&type=template&id=10bbae82&scoped=true&");
/* harmony import */ var _ResizingComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ResizingComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/specials/ResizingComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _ResizingComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _ResizingComponent_vue_vue_type_template_id_10bbae82_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _ResizingComponent_vue_vue_type_template_id_10bbae82_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "10bbae82",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/specials/ResizingComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/views/Testing.vue":
/*!****************************************!*\
  !*** ./resources/js/views/Testing.vue ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Testing_vue_vue_type_template_id_14793e41_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Testing.vue?vue&type=template&id=14793e41&scoped=true& */ "./resources/js/views/Testing.vue?vue&type=template&id=14793e41&scoped=true&");
/* harmony import */ var _Testing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Testing.vue?vue&type=script&lang=js& */ "./resources/js/views/Testing.vue?vue&type=script&lang=js&");
/* harmony import */ var _Testing_vue_vue_type_style_index_0_id_14793e41_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Testing.vue?vue&type=style&index=0&id=14793e41&lang=scss&scoped=true& */ "./resources/js/views/Testing.vue?vue&type=style&index=0&id=14793e41&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__.default)(
  _Testing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _Testing_vue_vue_type_template_id_14793e41_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _Testing_vue_vue_type_template_id_14793e41_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "14793e41",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/Testing.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/AssessmentDetails.vue?vue&type=script&lang=js&":
/*!********************************************************************************!*\
  !*** ./resources/js/components/AssessmentDetails.vue?vue&type=script&lang=js& ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentDetails_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentDetails.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentDetails.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentDetails_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/AssessmentSingle.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/js/components/AssessmentSingle.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSingle_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSingle.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentSingle.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSingle_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

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

/***/ "./resources/js/components/GreyButton.vue?vue&type=script&lang=js&":
/*!*************************************************************************!*\
  !*** ./resources/js/components/GreyButton.vue?vue&type=script&lang=js& ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_GreyButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./GreyButton.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/GreyButton.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_GreyButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/ItemRequestSection.vue?vue&type=script&lang=js&":
/*!*********************************************************************************!*\
  !*** ./resources/js/components/ItemRequestSection.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemRequestSection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ItemRequestSection.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemRequestSection.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemRequestSection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/ItemViewCover.vue?vue&type=script&lang=js&":
/*!****************************************************************************!*\
  !*** ./resources/js/components/ItemViewCover.vue?vue&type=script&lang=js& ***!
  \****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemViewCover_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ItemViewCover.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemViewCover.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemViewCover_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/LessonBoard.vue?vue&type=script&lang=js&":
/*!**************************************************************************!*\
  !*** ./resources/js/components/LessonBoard.vue?vue&type=script&lang=js& ***!
  \**************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LessonBoard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LessonBoard.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LessonBoard.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LessonBoard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

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

/***/ "./resources/js/components/MainSelect.vue?vue&type=script&lang=js&":
/*!*************************************************************************!*\
  !*** ./resources/js/components/MainSelect.vue?vue&type=script&lang=js& ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MainSelect_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MainSelect.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainSelect.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MainSelect_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

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

/***/ "./resources/js/components/RadioInput.vue?vue&type=script&lang=js&":
/*!*************************************************************************!*\
  !*** ./resources/js/components/RadioInput.vue?vue&type=script&lang=js& ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RadioInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./RadioInput.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RadioInput.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RadioInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/SpecialButton.vue?vue&type=script&lang=js&":
/*!****************************************************************************!*\
  !*** ./resources/js/components/SpecialButton.vue?vue&type=script&lang=js& ***!
  \****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SpecialButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SpecialButton.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/SpecialButton.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SpecialButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/TheBoard.vue?vue&type=script&lang=js&":
/*!***********************************************************************!*\
  !*** ./resources/js/components/TheBoard.vue?vue&type=script&lang=js& ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TheBoard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./TheBoard.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/TheBoard.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TheBoard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

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

/***/ "./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerMarkingBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AnswerMarkingBadge.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerMarkingBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

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

/***/ "./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionMarkingBadge.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/dashboard/AssessmentSectionMiniBadge.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionMiniBadge.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMiniBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionMiniBadge.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMiniBadge.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMiniBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

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

/***/ "./resources/js/components/dashboard/QuestionBadge.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/js/components/dashboard/QuestionBadge.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./QuestionBadge.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

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

/***/ "./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionMarkingForm.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

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

/***/ "./resources/js/components/specials/ResizingComponent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/components/specials/ResizingComponent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ResizingComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ResizingComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/ResizingComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ResizingComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/views/Testing.vue?vue&type=script&lang=js&":
/*!*****************************************************************!*\
  !*** ./resources/js/views/Testing.vue?vue&type=script&lang=js& ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Testing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Testing.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Testing.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Testing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true&":
/*!*******************************************************************************************************************!*\
  !*** ./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true& ***!
  \*******************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_FilesPreviewBackend_vue_vue_type_style_index_0_id_daab0f30_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader/dist/cjs.js!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/FilesPreviewBackend.vue?vue&type=style&index=0&id=daab0f30&lang=scss&scoped=true&");


/***/ }),

/***/ "./resources/js/components/GreyButton.vue?vue&type=style&index=0&id=d0d2f1c8&lang=scss&scoped=true&":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/components/GreyButton.vue?vue&type=style&index=0&id=d0d2f1c8&lang=scss&scoped=true& ***!
  \**********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_GreyButton_vue_vue_type_style_index_0_id_d0d2f1c8_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader/dist/cjs.js!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./GreyButton.vue?vue&type=style&index=0&id=d0d2f1c8&lang=scss&scoped=true& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/GreyButton.vue?vue&type=style&index=0&id=d0d2f1c8&lang=scss&scoped=true&");


/***/ }),

/***/ "./resources/js/components/ItemRequestSection.vue?vue&type=style&index=0&id=159bfc74&lang=scss&scoped=true&":
/*!******************************************************************************************************************!*\
  !*** ./resources/js/components/ItemRequestSection.vue?vue&type=style&index=0&id=159bfc74&lang=scss&scoped=true& ***!
  \******************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemRequestSection_vue_vue_type_style_index_0_id_159bfc74_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader/dist/cjs.js!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ItemRequestSection.vue?vue&type=style&index=0&id=159bfc74&lang=scss&scoped=true& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemRequestSection.vue?vue&type=style&index=0&id=159bfc74&lang=scss&scoped=true&");


/***/ }),

/***/ "./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true&":
/*!************************************************************************************************************!*\
  !*** ./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true& ***!
  \************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_MainCheckbox_vue_vue_type_style_index_0_id_49938287_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader/dist/cjs.js!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainCheckbox.vue?vue&type=style&index=0&id=49938287&lang=scss&scoped=true&");


/***/ }),

/***/ "./resources/js/components/MainSelect.vue?vue&type=style&index=0&id=4cf5d240&lang=scss&scoped=true&":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/components/MainSelect.vue?vue&type=style&index=0&id=4cf5d240&lang=scss&scoped=true& ***!
  \**********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_MainSelect_vue_vue_type_style_index_0_id_4cf5d240_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader/dist/cjs.js!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MainSelect.vue?vue&type=style&index=0&id=4cf5d240&lang=scss&scoped=true& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainSelect.vue?vue&type=style&index=0&id=4cf5d240&lang=scss&scoped=true&");


/***/ }),

/***/ "./resources/js/components/RadioInput.vue?vue&type=style&index=0&id=5c4565ba&lang=scss&scoped=true&":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/components/RadioInput.vue?vue&type=style&index=0&id=5c4565ba&lang=scss&scoped=true& ***!
  \**********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_RadioInput_vue_vue_type_style_index_0_id_5c4565ba_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader/dist/cjs.js!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./RadioInput.vue?vue&type=style&index=0&id=5c4565ba&lang=scss&scoped=true& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RadioInput.vue?vue&type=style&index=0&id=5c4565ba&lang=scss&scoped=true&");


/***/ }),

/***/ "./resources/js/components/SpecialButton.vue?vue&type=style&index=0&id=784cb920&lang=scss&scoped=true&":
/*!*************************************************************************************************************!*\
  !*** ./resources/js/components/SpecialButton.vue?vue&type=style&index=0&id=784cb920&lang=scss&scoped=true& ***!
  \*************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_SpecialButton_vue_vue_type_style_index_0_id_784cb920_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader/dist/cjs.js!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SpecialButton.vue?vue&type=style&index=0&id=784cb920&lang=scss&scoped=true& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/SpecialButton.vue?vue&type=style&index=0&id=784cb920&lang=scss&scoped=true&");


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

/***/ "./resources/js/components/dashboard/QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true&":
/*!***********************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true& ***!
  \***********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_style_index_0_id_3697b187_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader/dist/cjs.js!../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=style&index=0&id=3697b187&lang=scss&scoped=true&");


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

/***/ "./resources/js/views/Testing.vue?vue&type=style&index=0&id=14793e41&lang=scss&scoped=true&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/views/Testing.vue?vue&type=style&index=0&id=14793e41&lang=scss&scoped=true& ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_13_0_rules_0_use_3_node_modules_sass_resources_loader_lib_loader_js_clonedRuleSet_13_0_rules_0_use_4_node_modules_vue_loader_lib_index_js_vue_loader_options_Testing_vue_vue_type_style_index_0_id_14793e41_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader/dist/cjs.js!../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!../../../node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Testing.vue?vue&type=style&index=0&id=14793e41&lang=scss&scoped=true& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-13[0].rules[0].use[3]!./node_modules/sass-resources-loader/lib/loader.js??clonedRuleSet-13[0].rules[0].use[4]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Testing.vue?vue&type=style&index=0&id=14793e41&lang=scss&scoped=true&");


/***/ }),

/***/ "./resources/js/components/AssessmentDetails.vue?vue&type=template&id=7b16d245&scoped=true&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/components/AssessmentDetails.vue?vue&type=template&id=7b16d245&scoped=true& ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentDetails_vue_vue_type_template_id_7b16d245_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentDetails_vue_vue_type_template_id_7b16d245_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentDetails_vue_vue_type_template_id_7b16d245_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentDetails.vue?vue&type=template&id=7b16d245&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentDetails.vue?vue&type=template&id=7b16d245&scoped=true&");


/***/ }),

/***/ "./resources/js/components/AssessmentSingle.vue?vue&type=template&id=181a89d6&scoped=true&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/AssessmentSingle.vue?vue&type=template&id=181a89d6&scoped=true& ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSingle_vue_vue_type_template_id_181a89d6_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSingle_vue_vue_type_template_id_181a89d6_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSingle_vue_vue_type_template_id_181a89d6_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSingle.vue?vue&type=template&id=181a89d6&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentSingle.vue?vue&type=template&id=181a89d6&scoped=true&");


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

/***/ "./resources/js/components/GreyButton.vue?vue&type=template&id=d0d2f1c8&scoped=true&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/GreyButton.vue?vue&type=template&id=d0d2f1c8&scoped=true& ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_GreyButton_vue_vue_type_template_id_d0d2f1c8_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_GreyButton_vue_vue_type_template_id_d0d2f1c8_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_GreyButton_vue_vue_type_template_id_d0d2f1c8_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./GreyButton.vue?vue&type=template&id=d0d2f1c8&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/GreyButton.vue?vue&type=template&id=d0d2f1c8&scoped=true&");


/***/ }),

/***/ "./resources/js/components/ItemRequestSection.vue?vue&type=template&id=159bfc74&scoped=true&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/components/ItemRequestSection.vue?vue&type=template&id=159bfc74&scoped=true& ***!
  \***************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemRequestSection_vue_vue_type_template_id_159bfc74_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemRequestSection_vue_vue_type_template_id_159bfc74_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemRequestSection_vue_vue_type_template_id_159bfc74_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ItemRequestSection.vue?vue&type=template&id=159bfc74&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemRequestSection.vue?vue&type=template&id=159bfc74&scoped=true&");


/***/ }),

/***/ "./resources/js/components/ItemViewCover.vue?vue&type=template&id=5d205664&scoped=true&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/components/ItemViewCover.vue?vue&type=template&id=5d205664&scoped=true& ***!
  \**********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemViewCover_vue_vue_type_template_id_5d205664_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemViewCover_vue_vue_type_template_id_5d205664_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemViewCover_vue_vue_type_template_id_5d205664_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ItemViewCover.vue?vue&type=template&id=5d205664&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemViewCover.vue?vue&type=template&id=5d205664&scoped=true&");


/***/ }),

/***/ "./resources/js/components/LessonBoard.vue?vue&type=template&id=27c2d873&scoped=true&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/LessonBoard.vue?vue&type=template&id=27c2d873&scoped=true& ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LessonBoard_vue_vue_type_template_id_27c2d873_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LessonBoard_vue_vue_type_template_id_27c2d873_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LessonBoard_vue_vue_type_template_id_27c2d873_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LessonBoard.vue?vue&type=template&id=27c2d873&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LessonBoard.vue?vue&type=template&id=27c2d873&scoped=true&");


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

/***/ "./resources/js/components/MainSelect.vue?vue&type=template&id=4cf5d240&scoped=true&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/MainSelect.vue?vue&type=template&id=4cf5d240&scoped=true& ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MainSelect_vue_vue_type_template_id_4cf5d240_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MainSelect_vue_vue_type_template_id_4cf5d240_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MainSelect_vue_vue_type_template_id_4cf5d240_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MainSelect.vue?vue&type=template&id=4cf5d240&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainSelect.vue?vue&type=template&id=4cf5d240&scoped=true&");


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

/***/ "./resources/js/components/RadioInput.vue?vue&type=template&id=5c4565ba&scoped=true&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/RadioInput.vue?vue&type=template&id=5c4565ba&scoped=true& ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RadioInput_vue_vue_type_template_id_5c4565ba_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RadioInput_vue_vue_type_template_id_5c4565ba_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RadioInput_vue_vue_type_template_id_5c4565ba_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./RadioInput.vue?vue&type=template&id=5c4565ba&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RadioInput.vue?vue&type=template&id=5c4565ba&scoped=true&");


/***/ }),

/***/ "./resources/js/components/SpecialButton.vue?vue&type=template&id=784cb920&scoped=true&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/components/SpecialButton.vue?vue&type=template&id=784cb920&scoped=true& ***!
  \**********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SpecialButton_vue_vue_type_template_id_784cb920_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SpecialButton_vue_vue_type_template_id_784cb920_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SpecialButton_vue_vue_type_template_id_784cb920_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SpecialButton.vue?vue&type=template&id=784cb920&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/SpecialButton.vue?vue&type=template&id=784cb920&scoped=true&");


/***/ }),

/***/ "./resources/js/components/TheBoard.vue?vue&type=template&id=e7752040&scoped=true&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/components/TheBoard.vue?vue&type=template&id=e7752040&scoped=true& ***!
  \*****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TheBoard_vue_vue_type_template_id_e7752040_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TheBoard_vue_vue_type_template_id_e7752040_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TheBoard_vue_vue_type_template_id_e7752040_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./TheBoard.vue?vue&type=template&id=e7752040&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/TheBoard.vue?vue&type=template&id=e7752040&scoped=true&");


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

/***/ "./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=template&id=e316f95c&scoped=true&":
/*!*************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=template&id=e316f95c&scoped=true& ***!
  \*************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerMarkingBadge_vue_vue_type_template_id_e316f95c_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerMarkingBadge_vue_vue_type_template_id_e316f95c_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnswerMarkingBadge_vue_vue_type_template_id_e316f95c_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AnswerMarkingBadge.vue?vue&type=template&id=e316f95c&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=template&id=e316f95c&scoped=true&");


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

/***/ "./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=template&id=113c683b&scoped=true&":
/*!************************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=template&id=113c683b&scoped=true& ***!
  \************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingBadge_vue_vue_type_template_id_113c683b_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingBadge_vue_vue_type_template_id_113c683b_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingBadge_vue_vue_type_template_id_113c683b_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionMarkingBadge.vue?vue&type=template&id=113c683b&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=template&id=113c683b&scoped=true&");


/***/ }),

/***/ "./resources/js/components/dashboard/AssessmentSectionMiniBadge.vue?vue&type=template&id=29cbdc4f&scoped=true&":
/*!*********************************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/AssessmentSectionMiniBadge.vue?vue&type=template&id=29cbdc4f&scoped=true& ***!
  \*********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMiniBadge_vue_vue_type_template_id_29cbdc4f_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMiniBadge_vue_vue_type_template_id_29cbdc4f_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMiniBadge_vue_vue_type_template_id_29cbdc4f_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionMiniBadge.vue?vue&type=template&id=29cbdc4f&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMiniBadge.vue?vue&type=template&id=29cbdc4f&scoped=true&");


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

/***/ "./resources/js/components/dashboard/QuestionBadge.vue?vue&type=template&id=3697b187&scoped=true&":
/*!********************************************************************************************************!*\
  !*** ./resources/js/components/dashboard/QuestionBadge.vue?vue&type=template&id=3697b187&scoped=true& ***!
  \********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_template_id_3697b187_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_template_id_3697b187_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_QuestionBadge_vue_vue_type_template_id_3697b187_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./QuestionBadge.vue?vue&type=template&id=3697b187&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=template&id=3697b187&scoped=true&");


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

/***/ "./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=template&id=8e10cdbe&scoped=true&":
/*!*******************************************************************************************************************!*\
  !*** ./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=template&id=8e10cdbe&scoped=true& ***!
  \*******************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingForm_vue_vue_type_template_id_8e10cdbe_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingForm_vue_vue_type_template_id_8e10cdbe_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentSectionMarkingForm_vue_vue_type_template_id_8e10cdbe_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentSectionMarkingForm.vue?vue&type=template&id=8e10cdbe&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=template&id=8e10cdbe&scoped=true&");


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

/***/ "./resources/js/components/specials/ResizingComponent.vue?vue&type=template&id=10bbae82&scoped=true&":
/*!***********************************************************************************************************!*\
  !*** ./resources/js/components/specials/ResizingComponent.vue?vue&type=template&id=10bbae82&scoped=true& ***!
  \***********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ResizingComponent_vue_vue_type_template_id_10bbae82_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ResizingComponent_vue_vue_type_template_id_10bbae82_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ResizingComponent_vue_vue_type_template_id_10bbae82_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ResizingComponent.vue?vue&type=template&id=10bbae82&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/ResizingComponent.vue?vue&type=template&id=10bbae82&scoped=true&");


/***/ }),

/***/ "./resources/js/views/Testing.vue?vue&type=template&id=14793e41&scoped=true&":
/*!***********************************************************************************!*\
  !*** ./resources/js/views/Testing.vue?vue&type=template&id=14793e41&scoped=true& ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Testing_vue_vue_type_template_id_14793e41_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Testing_vue_vue_type_template_id_14793e41_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Testing_vue_vue_type_template_id_14793e41_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Testing.vue?vue&type=template&id=14793e41&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Testing.vue?vue&type=template&id=14793e41&scoped=true&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentDetails.vue?vue&type=template&id=7b16d245&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentDetails.vue?vue&type=template&id=7b16d245&scoped=true& ***!
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
  return _c(
    "div",
    { staticClass: "w-full min-h-90vh p-2" },
    [
      _c("div", { staticClass: "p-1 mb-4" }, [
        _c("div", { staticClass: "text-xs text-gray-300" }, [
          _vm._v("choose what to see")
        ]),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "flex flex-nowrap overflow-y-hidden overflow-x-auto" },
          [
            _c("radio-input", {
              attrs: { label: "all", radioValue: "all", name: "filter" },
              model: {
                value: _vm.filter,
                callback: function($$v) {
                  _vm.filter = $$v
                },
                expression: "filter"
              }
            }),
            _vm._v(" "),
            _c("radio-input", {
              attrs: {
                label: "participants",
                radioValue: "participants",
                name: "filter"
              },
              model: {
                value: _vm.filter,
                callback: function($$v) {
                  _vm.filter = $$v
                },
                expression: "filter"
              }
            }),
            _vm._v(" "),
            _c("radio-input", {
              attrs: {
                label: "markers",
                radioValue: "markers",
                name: "filter"
              },
              model: {
                value: _vm.filter,
                callback: function($$v) {
                  _vm.filter = $$v
                },
                expression: "filter"
              }
            })
          ],
          1
        ),
        _vm._v(" "),
        _c("div", { staticClass: "text-xs text-gray-300 my-1 text-center" }, [
          _vm._v(_vm._s(_vm.computedFilterMessage))
        ])
      ]),
      _vm._v(" "),
      _vm._l(_vm.computedFilteredAllParticipants, function(account) {
        return _c(
          "div",
          {
            key: "" + account.account + account.accountId,
            staticClass: "w-full border-b border-gray-400 p-2"
          },
          [
            _c(
              "div",
              { staticClass: "flex" },
              [
                _c(
                  "profile-picture",
                  { attrs: { classes: "w-10 h-10 min-h-10 flex-shrink-0" } },
                  [
                    _c("template", { slot: "image" }, [
                      _c("img", { attrs: { src: account.url } })
                    ])
                  ],
                  2
                ),
                _vm._v(" "),
                _c("div", { staticClass: "ml-2 w-full relative" }, [
                  _c("div", { staticClass: "text-gray-500 text-sm" }, [
                    _vm._v(
                      "\n                    " +
                        _vm._s(account.name) +
                        "\n                "
                    )
                  ]),
                  _vm._v(" "),
                  _c(
                    "div",
                    {
                      staticClass:
                        "text-right text-gray-400 text-xs font-semibold"
                    },
                    [
                      _vm._v(
                        "\n                    " +
                          _vm._s(account.account) +
                          "\n                "
                      )
                    ]
                  ),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "text-center text-gray-400 text-xs" },
                    [
                      _vm._v(
                        "\n                    " +
                          _vm._s(_vm.getDetails(account)) +
                          "\n                "
                      )
                    ]
                  ),
                  _vm._v(" "),
                  _vm.isYourAccount(account)
                    ? _c(
                        "div",
                        {
                          staticClass:
                            "absolute bottom-0 left-0 text-xs font-semibold text-gray-400"
                        },
                        [_vm._v("you")]
                      )
                    : _vm._e()
                ])
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "div",
              {
                staticClass:
                  "flex w-full overflow-y-hidden overflow-x-auto mt-1"
              },
              [
                _vm.canMark(account)
                  ? _c("special-button", {
                      staticClass: "p-1 mr-1 text-xs",
                      attrs: { buttonText: "mark work" },
                      on: {
                        click: function($event) {
                          return _vm.clickedButton("mark work", account)
                        }
                      }
                    })
                  : _vm._e()
              ],
              1
            )
          ]
        )
      })
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentSingle.vue?vue&type=template&id=181a89d6&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentSingle.vue?vue&type=template&id=181a89d6&scoped=true& ***!
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
  return _vm.assessment
    ? _c(
        "div",
        {
          staticClass: "assessment-single-wrapper min-w-1/2 min-h-90vh relative"
        },
        [
          _vm.steps
            ? _c(
                "div",
                {
                  staticClass:
                    "text-base p-2 cursor-pointer text-gray-500 absolute z-30 top-1 right-1",
                  on: { click: _vm.goBack }
                },
                [
                  _c("font-awesome-icon", {
                    attrs: { icon: ["fa", "long-arrow-alt-left"] }
                  })
                ],
                1
              )
            : _vm._e(),
          _vm._v(" "),
          [
            _c("auto-alert", {
              staticClass: "absolute w-full text-center top-1/2",
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
              staticClass: "absolute w-full text-center top-1/2 z-30",
              attrs: { loading: _vm.loading, size: "12px" }
            })
          ],
          _vm._v(" "),
          _vm.steps === 0
            ? _c(
                "item-view-cover",
                {
                  staticClass: "min-h-90vh",
                  attrs: {
                    data: _vm.computedCoverData,
                    additionalText: "still open"
                  }
                },
                [
                  _c(
                    "template",
                    { slot: "buttons" },
                    [
                      _vm.computedParticipant
                        ? _c("special-button", {
                            staticClass: "p-1 ml-5",
                            attrs: { buttonText: "take it" },
                            on: {
                              click: function($event) {
                                return _vm.clickedButton("take it")
                              }
                            }
                          })
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.computedMarker
                        ? _c("special-button", {
                            staticClass: "p-1 ml-5",
                            attrs: { buttonText: "mark" },
                            on: {
                              click: function($event) {
                                return _vm.clickedButton("mark")
                              }
                            }
                          })
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.computedCanJoin
                        ? _c("special-button", {
                            staticClass: "p-1 ml-5",
                            attrs: { buttonText: "want to take" },
                            on: {
                              click: function($event) {
                                return _vm.clickedButton("want to take")
                              }
                            }
                          })
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.computedMarkables.length
                        ? _c("special-button", {
                            staticClass: "p-1 ml-5",
                            attrs: { buttonText: "join markers" },
                            on: {
                              click: function($event) {
                                return _vm.clickedButton("join markers")
                              }
                            }
                          })
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.computedIsOwner
                        ? _c("special-button", {
                            staticClass: "p-1 ml-5",
                            attrs: { buttonText: "invite participant" },
                            on: {
                              click: function($event) {
                                return _vm.clickedButton("invite participant")
                              }
                            }
                          })
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.computedIsOwner
                        ? _c("special-button", {
                            staticClass: "p-1 ml-5",
                            attrs: { buttonText: "invite marker" },
                            on: {
                              click: function($event) {
                                return _vm.clickedButton("invite marker")
                              }
                            }
                          })
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.computedHasAnswered
                        ? _c("special-button", {
                            staticClass: "p-1 ml-5",
                            attrs: { buttonText: "view work" },
                            on: {
                              click: function($event) {
                                return _vm.clickedButton("view work")
                              }
                            }
                          })
                        : _vm._e()
                    ],
                    1
                  )
                ],
                2
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.steps || _vm.showProfiles
            ? _c(
                "div",
                {
                  class: [
                    _vm.showProfiles
                      ? ""
                      : "border-2 rounded-lg flex flex-col h-full"
                  ]
                },
                [
                  _vm.steps === 1
                    ? _c(
                        "div",
                        {
                          staticClass:
                            "p-3 flex flex-col h-90vh overflow-x-hidden overflow-y-auto justify-around"
                        },
                        [
                          _c(
                            "div",
                            {
                              staticClass:
                                "flex items-center text-gray-500 text-sm w-full mb-5"
                            },
                            [
                              _c("div", {}, [
                                _vm._v(
                                  "\n                    created by\n                "
                                )
                              ]),
                              _vm._v(" "),
                              _c(
                                "profile-picture",
                                {
                                  staticClass: "flex-shrink-0 my-1 mx-1",
                                  attrs: { classes: "h-7 w-7" }
                                },
                                [
                                  _c("template", { slot: "image" }, [
                                    _c("img", {
                                      attrs: { src: _vm.assessment.addedby.url }
                                    })
                                  ])
                                ],
                                2
                              ),
                              _vm._v(" "),
                              _c(
                                "div",
                                { staticClass: "font-semibold text-center" },
                                [
                                  _vm._v(
                                    "\n                    " +
                                      _vm._s(_vm.assessment.addedby.name) +
                                      "\n                "
                                  )
                                ]
                              )
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "div",
                            { staticClass: "w-full bg-gray-50 p-2 mb-10" },
                            [
                              _c(
                                "div",
                                { staticClass: "mx-1 text-lg font-bold" },
                                [
                                  _vm._v(
                                    "\n                    " +
                                      _vm._s(_vm.assessment.name) +
                                      "\n                "
                                  )
                                ]
                              ),
                              _vm._v(" "),
                              _c(
                                "div",
                                { staticClass: "mx-3 text-sm text-gray-500" },
                                [
                                  _vm._v(
                                    "\n                    " +
                                      _vm._s(_vm.assessment.description) +
                                      "\n                "
                                  )
                                ]
                              )
                            ]
                          ),
                          _vm._v(" "),
                          _c(
                            "div",
                            {
                              staticClass:
                                "w-full bg-gray-50 text-gray-500 p-2 text-sm mb-3"
                            },
                            [
                              _vm.assessment.duration
                                ? _c("div", [
                                    _vm._v(
                                      "\n                    " +
                                        _vm._s(
                                          "duration of " +
                                            _vm.assessment.duration +
                                            " mins"
                                        ) +
                                        "\n                "
                                    )
                                  ])
                                : _vm._e(),
                              _vm._v(" "),
                              _c("div", [
                                _vm._v(
                                  _vm._s(
                                    "total mark of " + _vm.assessment.totalMark
                                  )
                                )
                              ]),
                              _vm._v(" "),
                              _vm.assessment.dueAt
                                ? _c("div", [
                                    _vm._v(
                                      "\n                    " +
                                        _vm._s("due " + _vm.assessment.dueAt) +
                                        "\n                "
                                    )
                                  ])
                                : _vm._e(),
                              _vm._v(" "),
                              _c("div", [
                                _vm._v(
                                  "\n                    " +
                                    _vm._s(
                                      _vm.assessment.assessmentSections.length +
                                        " assessment sections"
                                    ) +
                                    "\n                "
                                )
                              ]),
                              _vm._v(" "),
                              _c("div", [
                                _vm._v(
                                  "\n                    " +
                                    _vm._s(
                                      _vm.computedNumberOfQuestions +
                                        " total questions"
                                    ) +
                                    "\n                "
                                )
                              ]),
                              _vm._v(" "),
                              _vm.assessment.worksCount
                                ? _c("div", [
                                    _vm._v(
                                      "\n                    " +
                                        _vm._s(
                                          _vm.assessment.worksCount +
                                            " persons have taken the assessment"
                                        ) +
                                        "\n                "
                                    )
                                  ])
                                : _vm._e()
                            ]
                          ),
                          _vm._v(" "),
                          _c(
                            "div",
                            {
                              staticClass:
                                "relative px-1 py-5 h-content max-h-1/3 flex w-full flex-nowrap mb-2 overflow-x-auto bg-gray-50 p-2"
                            },
                            [
                              _c(
                                "span",
                                {
                                  staticClass:
                                    "absolute text-gray-500 text-sm top-0"
                                },
                                [_vm._v("assessment sections")]
                              ),
                              _vm._v(" "),
                              _vm._l(
                                _vm.assessment.assessmentSections,
                                function(assessmentSection, index) {
                                  return _c("assessment-section-mini-badge", {
                                    key: index,
                                    staticClass: "min-w-full mx-1 flex-grow-0",
                                    attrs: {
                                      assessmentSection: assessmentSection
                                    }
                                  })
                                }
                              )
                            ],
                            2
                          ),
                          _vm._v(" "),
                          _c(
                            "div",
                            { staticClass: "flex justify-end" },
                            [
                              _vm.assessment.timer || _vm.computedHasAnswered
                                ? _c("special-button", {
                                    staticClass: "p-1 ml-5",
                                    attrs: { buttonText: "continue" },
                                    on: {
                                      click: function($event) {
                                        return _vm.clickedButton("continue")
                                      }
                                    }
                                  })
                                : _c("special-button", {
                                    staticClass: "p-1 ml-5",
                                    attrs: { buttonText: "start" },
                                    on: {
                                      click: function($event) {
                                        return _vm.clickedButton("start")
                                      }
                                    }
                                  })
                            ],
                            1
                          )
                        ]
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.steps === 2
                    ? _c(
                        "div",
                        {
                          staticClass: "p-2 h-90vh w-full flex flex-col",
                          class: {
                            "justify-center items-center": !_vm.computedTimingShow
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
                                          "flex text-gray-500 text-sm p-0.5 w-content mt-5",
                                        class: [
                                          _vm.timingItemHasFewTimeLeft
                                            ? "bg-red-700 text-gray-200"
                                            : "text-gray-500"
                                        ]
                                      },
                                      [
                                        _c("div", { staticClass: "mr-1" }, [
                                          _vm._v("Time remaining:")
                                        ]),
                                        _vm._v(" "),
                                        _c("div", [
                                          _vm._v(_vm._s(_vm.timingItemTimeLeft))
                                        ])
                                      ]
                                    )
                                  : _vm._e(),
                                _vm._v(" "),
                                _c("assessment-section-answering-form", {
                                  staticClass: "h-full mt-4",
                                  attrs: {
                                    assessment: _vm.assessment,
                                    work: _vm.work,
                                    answers: _vm.answers,
                                    numberOfQuestions:
                                      _vm.computedNumberOfQuestions,
                                    computedAccount: _vm.computedAccount
                                  },
                                  on: {
                                    initiated: _vm.initiatedAssessmentSection,
                                    goToStep: _vm.handleGoToStep,
                                    answered: _vm.answered
                                  }
                                }),
                                _vm._v(" "),
                                _vm.answers.length
                                  ? _c("special-button", {
                                      staticClass:
                                        "p-1 left-0 top-0 ml-0.5 mt-0.5 absolute",
                                      attrs: { buttonText: "submit answers" },
                                      on: {
                                        click: function($event) {
                                          return _vm.clickedButton(
                                            "submit answers"
                                          )
                                        }
                                      }
                                    })
                                  : _vm._e()
                              ]
                            : _vm._e(),
                          _vm._v(" "),
                          _vm.timingItemLocked
                            ? _c(
                                "div",
                                {
                                  staticClass:
                                    "p-2 rounded-sm text-gray-500 font-semibold text-sm"
                                },
                                [
                                  _vm._v(
                                    "\n                sorry 😕, you have either finished this assessment, its due, or there is no more time for answering\n            "
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
                                [
                                  _vm._v(
                                    "\n                ✋ wait for a while...\n            "
                                  )
                                ]
                              )
                            : _vm._e()
                        ],
                        2
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.steps === 3
                    ? _c(
                        "div",
                        { staticClass: "w-full h-full min-h-90vh" },
                        [
                          _c("assessment-details", {
                            staticClass: "mt-4",
                            attrs: { assessment: _vm.assessment },
                            on: { clickedButton: _vm.clickedButton }
                          })
                        ],
                        1
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.steps === 4
                    ? _c(
                        "div",
                        {
                          staticClass:
                            "w-full h-full flex flex-col justify-center items-center min-h-90vh"
                        },
                        [
                          _vm.computedShowSubmitMarks
                            ? _c("special-button", {
                                staticClass:
                                  "p-1 left-0 top-0 ml-0.5 mt-0.5 absolute",
                                attrs: { buttonText: "submit marks" },
                                on: {
                                  click: function($event) {
                                    return _vm.clickedButton("submit marks")
                                  }
                                }
                              })
                            : _vm._e(),
                          _vm._v(" "),
                          _vm.work
                            ? _c("assessment-section-marking-form", {
                                staticClass: "mt-5",
                                attrs: {
                                  work: _vm.work,
                                  assessment: _vm.assessment,
                                  marks: _vm.marks
                                },
                                on: {
                                  marked: _vm.marked,
                                  goToStep: _vm.handleGoToStep
                                }
                              })
                            : _c("div", {}, [_vm._v("getting the work...")])
                        ],
                        1
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.steps < 2
                    ? _c("reaction-component", {
                        staticClass: "flex-grow-0 flex-shrink-0 px-2",
                        attrs: {
                          comments: _vm.computedComments,
                          item: _vm.computedItem,
                          isOwner: _vm.computedIsOwner,
                          full: _vm.assessmentFull,
                          showAddComment: _vm.showAddComment,
                          showFlagReason: _vm.showFlagReason,
                          flagData: _vm.flagData,
                          likeData: _vm.likeData,
                          showProfilesText: _vm.showProfilesText,
                          classes: _vm.showOnlyProfiles
                            ? "absolute bottom-8"
                            : "",
                          showOnlyProfiles: _vm.showOnlyProfiles,
                          showProfiles: _vm.showProfiles,
                          profiles: _vm.computedProfiles
                        },
                        on: {
                          hideAddComment: function($event) {
                            _vm.showAddComment = false
                          },
                          postAddComplete: _vm.postAddComplete,
                          askLoginRegister: _vm.askLoginRegister,
                          clickedMedia: _vm.clickedMedia,
                          clickedProfile: _vm.clickedProfile,
                          clickedLike: _vm.clickedLike,
                          clickedAddComment: _vm.clickedAddComment,
                          cancelFlagProcess: _vm.cancelFlagProcess,
                          reasonGiven: _vm.reasonGiven,
                          clickedFlag: _vm.clickedFlag,
                          continueFlagProcess: _vm.continueFlagProcess,
                          clickedShowPostComments: _vm.clickedShowPostComments
                        }
                      })
                    : _vm._e()
                ],
                1
              )
            : _vm._e(),
          _vm._v(" "),
          _c("item-request-section", {
            attrs: {
              show: _vm.showRequest,
              computedItem: _vm.computedItem,
              hasAllowed: false,
              loading: _vm.loading,
              for: _vm.joinOrInvitationType,
              removedParticipant: _vm.removedParticipant
            },
            on: {
              doneRemovingParticipant: _vm.doneRemovingParticipant,
              clickedCloseRequest: _vm.closeItemRequestSection,
              clickedParticpantAction: _vm.clickedParticpantAction
            }
          }),
          _vm._v(" "),
          _c(
            "fade-up",
            [
              _vm.showSmallModal
                ? _c(
                    "template",
                    { slot: "transition" },
                    [
                      _c(
                        "small-modal",
                        {
                          attrs: {
                            title: _vm.smallModalMessage,
                            show: _vm.showSmallModal,
                            message: _vm.alertMessage,
                            success: _vm.alertSuccess,
                            danger: _vm.alertDanger,
                            loading: _vm.loading,
                            alerting: _vm.smallModalAlerting
                          },
                          on: { disappear: _vm.clearSmallModal }
                        },
                        [
                          _c(
                            "template",
                            { slot: "actions" },
                            [
                              _vm.smallModalInfo
                                ? _c("post-button", {
                                    attrs: { buttonText: "ok" },
                                    on: { click: _vm.clickedSmallModalButton }
                                  })
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.smallModalDelete
                                ? _c("post-button", {
                                    attrs: {
                                      buttonText: "yes",
                                      buttonStyle: "danger"
                                    },
                                    on: { click: _vm.clickedSmallModalButton }
                                  })
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.smallModalDelete
                                ? _c("post-button", {
                                    attrs: {
                                      buttonText: "no",
                                      buttonStyle: "success"
                                    },
                                    on: { click: _vm.clickedSmallModalButton }
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
                : _vm._e()
            ],
            2
          ),
          _vm._v(" "),
          _vm.flagData.myFlag
            ? _c("flag-cover", { attrs: { item: _vm.computedItem.item } })
            : _vm._e(),
          _vm._v(" "),
          _c("pop-up", {
            attrs: {
              show: _vm.showPopUp,
              responses: ["continue", "cancel"],
              default: "continue",
              message:
                "this assessment has a duration. once you click continue, a timer will start and you will have to finish before the timer completes"
            },
            on: {
              clickedResponse: _vm.clickedPopupResponse,
              closePopUp: _vm.closePopUp
            }
          })
        ],
        2
      )
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true



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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/GreyButton.vue?vue&type=template&id=d0d2f1c8&scoped=true&":
/*!**********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/GreyButton.vue?vue&type=template&id=d0d2f1c8&scoped=true& ***!
  \**********************************************************************************************************************************************************************************************************************************/
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
      staticClass: "grey-button-wrapper",
      class: { active: _vm.active },
      on: { click: _vm.clickedAction }
    },
    [
      _vm._v("\n    " + _vm._s(_vm.loading ? "" : _vm.text) + "\n    "),
      _c("pulse-loader", { attrs: { loading: _vm.loading, size: "6px" } })
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemRequestSection.vue?vue&type=template&id=159bfc74&scoped=true&":
/*!******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemRequestSection.vue?vue&type=template&id=159bfc74&scoped=true& ***!
  \******************************************************************************************************************************************************************************************************************************************/
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
        ? _c("template", { slot: "transition" }, [
            _c("div", { staticClass: "item-request-section" }, [
              _c(
                "div",
                {
                  staticClass: "close",
                  on: { click: _vm.clickedCloseRequest }
                },
                [_c("font-awesome-icon", { attrs: { icon: ["fa", "times"] } })],
                1
              ),
              _vm._v(" "),
              _c("div", { staticClass: "title" }, [
                _vm._v(_vm._s(_vm.computedTitle))
              ]),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "body" },
                [
                  _c("search-input", {
                    staticClass: "search-section",
                    attrs: { placeholder: "search whom to invite?" },
                    on: { search: _vm.receivedParticipantsSearchText }
                  }),
                  _vm._v(" "),
                  _vm.hasAllowed
                    ? _c(
                        "div",
                        { staticClass: "search-types" },
                        [
                          _vm.allowed === "ALL"
                            ? _c("grey-button", {
                                staticClass: "grey-button",
                                attrs: {
                                  active: _vm.searchType === "profiles",
                                  text: "all"
                                },
                                on: {
                                  clickedAction: function($event) {
                                    return _vm.clickedSearchType("profiles")
                                  }
                                }
                              })
                            : _vm._e(),
                          _vm._v(" "),
                          _vm.allowed === "ALL" || _vm.allowed === "LEARNERS"
                            ? _c("grey-button", {
                                staticClass: "grey-button",
                                attrs: {
                                  active: _vm.searchType === "learners",
                                  text: "learners"
                                },
                                on: {
                                  clickedAction: function($event) {
                                    return _vm.clickedSearchType("learners")
                                  }
                                }
                              })
                            : _vm._e(),
                          _vm._v(" "),
                          _vm.allowed === "ALL" || _vm.allowed === "PARENTS"
                            ? _c("grey-button", {
                                staticClass: "grey-button",
                                attrs: {
                                  active: _vm.searchType === "parents",
                                  text: "parents"
                                },
                                on: {
                                  clickedAction: function($event) {
                                    return _vm.clickedSearchType("parents")
                                  }
                                }
                              })
                            : _vm._e(),
                          _vm._v(" "),
                          _vm.allowed === "ALL" ||
                          _vm.allowed === "FACILITATORS"
                            ? _c("grey-button", {
                                staticClass: "grey-button",
                                attrs: {
                                  active: _vm.searchType === "facilitators",
                                  text: "facilitators"
                                },
                                on: {
                                  clickedAction: function($event) {
                                    return _vm.clickedSearchType("facilitators")
                                  }
                                }
                              })
                            : _vm._e(),
                          _vm._v(" "),
                          _vm.allowed === "ALL" ||
                          _vm.allowed === "PROFESSIONALS"
                            ? _c("grey-button", {
                                staticClass: "grey-button",
                                attrs: {
                                  active: _vm.searchType === "professionals",
                                  text: "professionals"
                                },
                                on: {
                                  clickedAction: function($event) {
                                    return _vm.clickedSearchType(
                                      "professionals"
                                    )
                                  }
                                }
                              })
                            : _vm._e(),
                          _vm._v(" "),
                          _vm.allowed === "ALL" || _vm.allowed === "SCHOOLS"
                            ? _c("grey-button", {
                                staticClass: "grey-button",
                                attrs: {
                                  active: _vm.searchType === "schools",
                                  text: "schools"
                                },
                                on: {
                                  clickedAction: function($event) {
                                    return _vm.clickedSearchType("schools")
                                  }
                                }
                              })
                            : _vm._e()
                        ],
                        1
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "accounts-section" },
                    [
                      !_vm.searchLoading && _vm.noSearchParticipants
                        ? _c("div", { staticClass: "no-participants" }, [
                            _vm._v(
                              "\n                        no search results\n                    "
                            )
                          ])
                        : _vm._e(),
                      _vm._v(" "),
                      _vm._l(_vm.searchParticipants, function(
                        participant,
                        index
                      ) {
                        return _c("participant-badge", {
                          key: index,
                          staticClass: "participant-badge",
                          attrs: {
                            account: participant,
                            invite: true,
                            inviting: _vm.loading
                          },
                          on: { clickedAction: _vm.clickedParticpantAction }
                        })
                      }),
                      _vm._v(" "),
                      _vm.searchLoading
                        ? _c(
                            "div",
                            { staticClass: "loading" },
                            [
                              _c("pulse-loader", {
                                attrs: {
                                  loading: _vm.searchLoading,
                                  size: "10px"
                                }
                              })
                            ],
                            1
                          )
                        : _vm._e(),
                      _vm._v(" "),
                      !_vm.searchLoading && _vm.showMoreSearchParticipants
                        ? _c("infinite-loader", {
                            on: { infinite: _vm.infiniteHandler }
                          })
                        : _vm._e()
                    ],
                    2
                  )
                ],
                1
              )
            ])
          ])
        : _vm._e()
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemViewCover.vue?vue&type=template&id=5d205664&scoped=true&":
/*!*************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ItemViewCover.vue?vue&type=template&id=5d205664&scoped=true& ***!
  \*************************************************************************************************************************************************************************************************************************************/
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
  return _vm.data
    ? _c(
        "div",
        {
          staticClass:
            "flex flex-col h-full justify-between p-3 border-2 rounded-lg"
        },
        [
          _c(
            "div",
            {
              staticClass:
                "text-gray-500 text-sm lowercase flex justify-between w-full"
            },
            [
              _vm.computedTypeText.length
                ? _c("div", [
                    _vm._v(
                      "\n            " +
                        _vm._s(_vm.computedTypeText) +
                        "\n        "
                    )
                  ])
                : _vm._e(),
              _vm._v(" "),
              _vm.additionalText.length
                ? _c("div", [
                    _vm._v(
                      "\n            " +
                        _vm._s(_vm.additionalText) +
                        "\n        "
                    )
                  ])
                : _vm._e()
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "mx-auto min-w-3/4 max-w-full p-2 bg-gray-50",
              class: { "text-transparent": _vm.transparent }
            },
            [
              _vm.data.name
                ? _c("div", { staticClass: "capitalize text-lg font-black" }, [
                    _vm._v(
                      "\n            " + _vm._s(_vm.data.name) + "\n        "
                    )
                  ])
                : _vm._e(),
              _vm._v(" "),
              _vm.data.description
                ? _c("div", { staticClass: "pt-10 text-center truncate" }, [
                    _vm._v(
                      "\n            " +
                        _vm._s(_vm.data.description) +
                        "\n        "
                    )
                  ])
                : _vm._e()
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "flex p-2 items-center overflow-y-auto" },
            [_vm._t("buttons")],
            2
          )
        ]
      )
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LessonBoard.vue?vue&type=template&id=27c2d873&scoped=true&":
/*!***********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/LessonBoard.vue?vue&type=template&id=27c2d873&scoped=true& ***!
  \***********************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "w-full min-h-screen bg-whitesmoke relative px-2 pb-2" },
    [
      _c("auto-alert", {
        staticClass: "absolute w-full text-center top-1/2",
        attrs: {
          message: _vm.alertMessage,
          success: _vm.alertSuccess,
          danger: _vm.alertDanger,
          sticky: true
        },
        on: {
          hideAlert: _vm.clearAlert,
          doneShowingDangerMessage: _vm.afterDangerAlert,
          stop: _vm.stopResizingAndMoving
        }
      }),
      _vm._v(" "),
      _c(
        "div",
        {
          staticClass:
            "fixed rounded-full cursor-pointer\n            text-whitesmoke text-sm h-content w-content p-2 \n            bottom-1 right-1 z-50"
        },
        [
          _c(
            "div",
            {
              staticClass:
                "rounded-full my-2 bg-green-600 cursor-pointer\n                text-whitesmoke text-sm h-content w-content p-2",
              attrs: { title: "add a new board" },
              on: {
                click: function($event) {
                  return _vm.clickedButton("add board")
                }
              }
            },
            [_c("font-awesome-icon", { attrs: { icon: ["fa", "plus"] } })],
            1
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass:
                "rounded-full my-2 bg-gray-600 cursor-pointer\n                text-whitesmoke text-sm h-content w-content p-2",
              attrs: { title: "show other tools" },
              on: {
                click: function($event) {
                  return _vm.clickedButton("tools")
                }
              }
            },
            [_c("font-awesome-icon", { attrs: { icon: ["fa", "wrench"] } })],
            1
          ),
          _vm._v(" "),
          _c("div", {
            ref: "activecolor",
            staticClass:
              "w-8 h-8 my-2 cursor-pointer rounded-full border-gray-900 border-2",
            on: {
              click: function($event) {
                return _vm.clickedButton("colors")
              }
            }
          })
        ]
      ),
      _vm._v(" "),
      _vm._l(_vm.boards, function(board) {
        return _c(
          "resizing-component",
          {
            key: board.id,
            staticClass: "w-content h-content",
            attrs: {
              resize: _vm.resize,
              move: _vm.move,
              hasNegativeTop: _vm.getBoardIndex(board) > 0
            },
            on: {
              startedResizing: _vm.startedResizing,
              stoppedResizing: _vm.stoppedResizing
            }
          },
          [
            _c("the-board", {
              key: board.name,
              staticClass: "mb-10",
              attrs: {
                board: board,
                resize: board.name === _vm.resizedBoard.name,
                lineWidth: _vm.lineWidth,
                activeColor: _vm.selectedColor,
                what: _vm.computedDrawWhat,
                angle: _vm.drawObject.angle,
                sides: _vm.drawObject.sides
              },
              on: { editBoard: _vm.editBoard, deleteBoard: _vm.deleteBoard }
            })
          ],
          1
        )
      }),
      _vm._v(" "),
      _vm.showItem === "accounts"
        ? _c(
            "div",
            { staticClass: "w-full flex justify-start items-center" },
            _vm._l(_vm.accounts, function(account, index) {
              return _c(
                "profile-picture",
                { key: index, staticClass: "w-10 h-10" },
                [[_c("img", { attrs: { src: account.url, alt: "" } })]],
                2
              )
            }),
            1
          )
        : _vm._e(),
      _vm._v(" "),
      _c(
        "pop-up",
        {
          attrs: {
            show: _vm.showPopUp,
            responses: _vm.responses,
            message: _vm.popUpMessage,
            default: "cancel",
            hasResponses: _vm.popUpHasResponses
          },
          on: {
            clickedResponse: _vm.clickedPopupResponse,
            closePopUp: _vm.closePopUp
          }
        },
        [
          _vm.showItem === "board form"
            ? _c(
                "div",
                [
                  _c("text-input", {
                    staticClass: "mb-2",
                    attrs: {
                      bottomBorder: true,
                      placeholder: "name of the new board"
                    },
                    nativeOn: {
                      keydown: function($event) {
                        if (
                          !$event.type.indexOf("key") &&
                          _vm._k(
                            $event.keyCode,
                            "enter",
                            13,
                            $event.key,
                            "Enter"
                          )
                        ) {
                          return null
                        }
                        return _vm.clickedPopupResponse("ok")
                      }
                    },
                    model: {
                      value: _vm.board.name,
                      callback: function($$v) {
                        _vm.$set(_vm.board, "name", $$v)
                      },
                      expression: "board.name"
                    }
                  })
                ],
                1
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.showItem === "tools"
            ? _c("div", [
                _c(
                  "div",
                  {
                    staticClass:
                      "flex flex-wrap w-full items-center justify-center"
                  },
                  _vm._l(_vm.colors, function(color, index) {
                    return _c("div", {
                      key: index,
                      staticClass:
                        "w-8 h-8 m-2 cursor-pointer hover:w-12 hover:h-12 rounded-full",
                      class: [color ? "bg-" + color.name + "-500" : "bg-black"],
                      on: {
                        click: function($event) {
                          return _vm.clickedColor(color.value)
                        }
                      }
                    })
                  }),
                  0
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "flex w-full flex-wrap items-center" },
                  [
                    _c("input", {
                      staticClass: "mx-2",
                      attrs: { type: "color", title: "more colors" },
                      domProps: { value: "" + _vm.selectedColor },
                      on: {
                        input: function($event) {
                          return _vm.clickedColor($event.target.value)
                        }
                      }
                    }),
                    _vm._v(" "),
                    _c("input", {
                      staticClass: "mx-2",
                      attrs: {
                        type: "range",
                        title: "select line width",
                        min: "1",
                        max: "100"
                      },
                      domProps: { value: _vm.lineWidth },
                      on: { change: _vm.setLineWidth, input: _vm.setLineWidth }
                    }),
                    _vm._v(" "),
                    _c("main-checkbox", {
                      attrs: { label: "resize" },
                      model: {
                        value: _vm.resize,
                        callback: function($$v) {
                          _vm.resize = $$v
                        },
                        expression: "resize"
                      }
                    }),
                    _vm._v(" "),
                    _c("main-checkbox", {
                      attrs: { label: "move" },
                      model: {
                        value: _vm.move,
                        callback: function($$v) {
                          _vm.move = $$v
                        },
                        expression: "move"
                      }
                    })
                  ],
                  1
                ),
                _vm._v(" "),
                _c(
                  "div",
                  {},
                  [
                    _c("main-checkbox", {
                      attrs: { label: "freehand" },
                      model: {
                        value: _vm.drawObject.freehand,
                        callback: function($$v) {
                          _vm.$set(_vm.drawObject, "freehand", $$v)
                        },
                        expression: "drawObject.freehand"
                      }
                    }),
                    _vm._v(" "),
                    _c("main-checkbox", {
                      attrs: { label: "line" },
                      model: {
                        value: _vm.drawObject.line,
                        callback: function($$v) {
                          _vm.$set(_vm.drawObject, "line", $$v)
                        },
                        expression: "drawObject.line"
                      }
                    }),
                    _vm._v(" "),
                    _c("main-checkbox", {
                      attrs: { label: "polygon" },
                      model: {
                        value: _vm.drawObject.polygon,
                        callback: function($$v) {
                          _vm.$set(_vm.drawObject, "polygon", $$v)
                        },
                        expression: "drawObject.polygon"
                      }
                    }),
                    _vm._v(" "),
                    _c("main-checkbox", {
                      attrs: { label: "circle" },
                      model: {
                        value: _vm.drawObject.circle,
                        callback: function($$v) {
                          _vm.$set(_vm.drawObject, "circle", $$v)
                        },
                        expression: "drawObject.circle"
                      }
                    }),
                    _vm._v(" "),
                    _vm.drawObject.polygon
                      ? _c("input", {
                          directives: [
                            {
                              name: "model",
                              rawName: "v-model",
                              value: _vm.drawObject.angle,
                              expression: "drawObject.angle"
                            }
                          ],
                          staticClass: "mx-2 mb-2",
                          attrs: {
                            type: "range",
                            title: "select angle",
                            min: "0",
                            max: "3600"
                          },
                          domProps: { value: _vm.drawObject.angle },
                          on: {
                            __r: function($event) {
                              return _vm.$set(
                                _vm.drawObject,
                                "angle",
                                $event.target.value
                              )
                            }
                          }
                        })
                      : _vm._e(),
                    _vm._v(" "),
                    _vm.drawObject.polygon
                      ? _c("number-input", {
                          attrs: {
                            hasMin: true,
                            inputMin: 3,
                            inputMax: 16,
                            placeholder: "enter number of sides of polygon"
                          },
                          model: {
                            value: _vm.drawObject.sides,
                            callback: function($$v) {
                              _vm.$set(_vm.drawObject, "sides", $$v)
                            },
                            expression: "drawObject.sides"
                          }
                        })
                      : _vm._e()
                  ],
                  1
                )
              ])
            : _vm._e()
        ]
      )
    ],
    2
  )
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainSelect.vue?vue&type=template&id=4cf5d240&scoped=true&":
/*!**********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/MainSelect.vue?vue&type=template&id=4cf5d240&scoped=true& ***!
  \**********************************************************************************************************************************************************************************************************************************/
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
      staticClass: "main-select-wrapper",
      on: {
        focus: _vm.clickedSelect,
        keydown: function($event) {
          if (
            !$event.type.indexOf("key") &&
            _vm._k($event.keyCode, "tab", 9, $event.key, "Tab")
          ) {
            return null
          }
          return _vm.clickedSelect.apply(null, arguments)
        }
      }
    },
    [
      _c(
        "div",
        {
          ref: "selectedsection",
          staticClass: "selected-section",
          class: { active: _vm.value.length, "has-radius": _vm.hasRadius },
          on: {
            click: function($event) {
              if ($event.target !== $event.currentTarget) {
                return null
              }
              return _vm.clickedSelect.apply(null, arguments)
            }
          }
        },
        [
          _vm._v(
            "\n        " +
              _vm._s(_vm.value.length ? _vm.value : _vm.placeholder) +
              "\n        "
          ),
          _c(
            "div",
            { staticClass: "icon", on: { click: _vm.clickedSelect } },
            [
              _vm.activeIcon === "up"
                ? _c("font-awesome-icon", {
                    attrs: { icon: ["fa", "chevron-down"] }
                  })
                : _vm._e(),
              _vm._v(" "),
              _vm.activeIcon === "down"
                ? _c("font-awesome-icon", {
                    attrs: { icon: ["fa", "chevron-up"] }
                  })
                : _vm._e()
            ],
            1
          )
        ]
      ),
      _vm._v(" "),
      _c(
        "div",
        {
          ref: "dropdownsection",
          staticClass: "dropdown-section",
          class: { active: _vm.activeIcon === "down" }
        },
        _vm._l(_vm.computedItems, function(item, index) {
          return _c(
            "div",
            {
              key: index,
              staticClass: "dropdown-item",
              class: { active: _vm.value === item },
              on: {
                click: function($event) {
                  return _vm.clickedItem(item)
                }
              }
            },
            [
              _vm._v(
                "\n            " +
                  _vm._s(item.name ? item.name : item) +
                  "\n        "
              )
            ]
          )
        }),
        0
      )
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RadioInput.vue?vue&type=template&id=5c4565ba&scoped=true&":
/*!**********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RadioInput.vue?vue&type=template&id=5c4565ba&scoped=true& ***!
  \**********************************************************************************************************************************************************************************************************************************/
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
      staticClass: "check-input-wrapper",
      class: { checked: _vm.value === _vm.radioValue }
    },
    [
      _c("input", {
        ref: "radioinput",
        attrs: { type: "radio", name: _vm.name, id: _vm.id },
        domProps: { value: _vm.computedValue },
        on: { change: _vm.inputRadioMethod }
      }),
      _vm._v(" "),
      _c("label", { staticClass: "label", attrs: { for: _vm.id } }, [
        _vm._v(_vm._s(_vm.label))
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/SpecialButton.vue?vue&type=template&id=784cb920&scoped=true&":
/*!*************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/SpecialButton.vue?vue&type=template&id=784cb920&scoped=true& ***!
  \*************************************************************************************************************************************************************************************************************************************/
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
      staticClass: "btn min-w-content max-h-content",
      class: { "btn-size": !_vm.computedHasFontSizeClasses },
      attrs: { title: _vm.titleText },
      on: { click: _vm.onClick }
    },
    [_vm._v("\n    " + _vm._s(_vm.buttonText) + "\n    "), _vm._t("loader")],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/TheBoard.vue?vue&type=template&id=e7752040&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/TheBoard.vue?vue&type=template&id=e7752040&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************************/
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
  return _vm.board
    ? _c("div", { staticClass: "w-80 h-80" }, [
        _c(
          "div",
          {
            staticClass: "text-xs absolute ml-1  mt-1 p-1 z-10 cursor-pointer",
            class: [
              _vm.backgroundColor === "white"
                ? "text-gray-500"
                : "text-gray-100"
            ],
            on: { dblclick: _vm.editBoard }
          },
          [_vm._v(_vm._s(_vm.board.name))]
        ),
        _vm._v(" "),
        _c(
          "div",
          {
            staticClass:
              "text-xs absolute right-0 ml-1  mt-1 p-1 z-10 cursor-pointer",
            class: [
              _vm.backgroundColor === "white"
                ? "text-gray-500"
                : "text-gray-100"
            ],
            on: { dblclick: _vm.deleteBoard }
          },
          [_c("font-awesome-icon", { attrs: { icon: ["fa", "trash"] } })],
          1
        ),
        _vm._v(" "),
        _c("canvas", {
          ref: "canvas",
          staticClass: "w-full h-full max-w-2xl mx-auto mt-2 border-2",
          on: {
            mousedown: function($event) {
              $event.preventDefault()
              return _vm.start.apply(null, arguments)
            },
            mousemove: function($event) {
              $event.preventDefault()
              return _vm.draw.apply(null, arguments)
            },
            mouseup: function($event) {
              $event.preventDefault()
              return _vm.stop.apply(null, arguments)
            },
            mouseout: function($event) {
              $event.preventDefault()
              return _vm.stop.apply(null, arguments)
            }
          }
        }),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "flex justify-center items-center bg-whitesmoke" },
          [
            _c(
              "button",
              {
                staticClass:
                  "text-gray-500 p-1 mx-2 text-sm border-b cursor-pointer hover:shadow-sm hover:bg-gray-50 rounded",
                on: {
                  click: function($event) {
                    return _vm.clickedButton("undo")
                  }
                }
              },
              [_vm._v("undo")]
            ),
            _vm._v(" "),
            _c(
              "button",
              {
                staticClass:
                  "text-gray-500 p-1 mx-2 text-sm border-b cursor-pointer hover:shadow-sm hover:bg-gray-50 rounded",
                on: {
                  click: function($event) {
                    return _vm.clickedButton("clear")
                  }
                }
              },
              [_vm._v("clear")]
            ),
            _vm._v(" "),
            _c("font-awesome-icon", {
              staticClass: "cursor-pointer mx-2 text-2xl",
              attrs: {
                icon: ["fa", "toggle-" + _vm.toggleType],
                title: "toggle between black and white board"
              },
              on: { click: _vm.toggleBackgroundType }
            })
          ],
          1
        )
      ])
    : _vm._e()
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=template&id=e316f95c&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AnswerMarkingBadge.vue?vue&type=template&id=e316f95c&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************************/
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
            on: { clickedButton: _vm.clickedMarkButton }
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=template&id=113c683b&scoped=true&":
/*!***************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMarkingBadge.vue?vue&type=template&id=113c683b&scoped=true& ***!
  \***************************************************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "p-2" },
    [
      _c("assessment-section-information-badge", {
        staticClass: "flex-shrink-0",
        attrs: { assessmentSection: _vm.assessmentSection }
      }),
      _vm._v(" "),
      _c(
        "div",
        {
          staticClass:
            "min-h-90vh flex-shrink mb-2 overflow-y-auto p-2 overflow-x-hidden"
        },
        _vm._l(_vm.answers, function(answer) {
          return _c("answer-marking-badge", {
            key: answer.id,
            attrs: {
              answer: answer,
              providedMark: _vm.getSpecificMark(answer)
            },
            on: { marked: _vm.marked, performActionOnMark: _vm.marked }
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMiniBadge.vue?vue&type=template&id=29cbdc4f&scoped=true&":
/*!************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/AssessmentSectionMiniBadge.vue?vue&type=template&id=29cbdc4f&scoped=true& ***!
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
  return _vm.assessmentSection
    ? _c("div", { staticClass: "bg-white rounded-sm p-2" }, [
        _c("div", { staticClass: "text-center" }, [
          _vm._v(_vm._s(_vm.assessmentSection.name))
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "text-sm text-gray-500" }, [
          _vm._v(_vm._s(_vm.assessmentSection.instruction))
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "text-right text-xs" }, [
          _vm._v(_vm._s(_vm.assessmentSection.questions.length + " questions"))
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=template&id=3697b187&scoped=true&":
/*!***********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/QuestionBadge.vue?vue&type=template&id=3697b187&scoped=true& ***!
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=template&id=8e10cdbe&scoped=true&":
/*!**********************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/forms/AssessmentSectionMarkingForm.vue?vue&type=template&id=8e10cdbe&scoped=true& ***!
  \**********************************************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "w-full mt-4" },
    [
      _vm.computedNotAddedby
        ? [
            _c("assessment-section-marking-badge", {
              staticClass: "min-h-90vh flex flex-col",
              attrs: {
                assessmentSection: _vm.currentAssessmentSection,
                answers: _vm.computedAnswers,
                marks: _vm.computedMarks
              },
              on: { marked: _vm.marked }
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
          ]
        : _vm._e(),
      _vm._v(" "),
      _c("div", { staticClass: "text-gray-500 text-center text-sm" }, [
        _vm._v("\n        sorry, you cannot mark your own work 😏\n    ")
      ])
    ],
    2
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



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/ResizingComponent.vue?vue&type=template&id=10bbae82&scoped=true&":
/*!**************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/specials/ResizingComponent.vue?vue&type=template&id=10bbae82&scoped=true& ***!
  \**************************************************************************************************************************************************************************************************************************************************/
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
      staticClass: "relative",
      class: { "cursor-move": _vm.move },
      on: { mousedown: _vm.startMoving, dblclick: _vm.stop }
    },
    [
      _c(
        "div",
        {
          class: [
            _vm.resize || _vm.move
              ? "absolute h-full w-full top-0 left-0 border-2 border-blue-300"
              : ""
          ]
        },
        [
          _vm.resize
            ? [
                _c("div", {
                  staticClass:
                    "absolute -left-1 -top-1 h-2 w-2 cursor-nwse \n                    rounded-full bg-blue-300",
                  attrs: { "data-button": "top-left" },
                  on: { mousedown: _vm.startResize }
                }),
                _vm._v(" "),
                _c("div", {
                  staticClass:
                    "absolute -left-1 -bottom-1 h-2 w-2 cursor-nesw \n                    rounded-full bg-blue-300",
                  attrs: { "data-button": "bottom-left" },
                  on: { mousedown: _vm.startResize }
                }),
                _vm._v(" "),
                _c("div", {
                  staticClass:
                    "absolute -right-1 -top-1 h-2 w-2 cursor-nesw \n                    rounded-full bg-blue-300",
                  attrs: { "data-button": "top-right" },
                  on: { mousedown: _vm.startResize }
                }),
                _vm._v(" "),
                _c("div", {
                  staticClass:
                    "absolute -right-1 -bottom-1 h-2 w-2 cursor-nwse \n                    rounded-full bg-blue-300",
                  attrs: { "data-button": "bottom-right" },
                  on: { mousedown: _vm.startResize }
                })
              ]
            : _vm._e()
        ],
        2
      ),
      _vm._v(" "),
      _vm._t("default")
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Testing.vue?vue&type=template&id=14793e41&scoped=true&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Testing.vue?vue&type=template&id=14793e41&scoped=true& ***!
  \**************************************************************************************************************************************************************************************************************************/
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
  return _c("lesson-board")
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);