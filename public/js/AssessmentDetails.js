(self["webpackChunk"] = self["webpackChunk"] || []).push([["AssessmentDetails"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentDetails.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentDetails.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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
      if (!this.computedCanMark) {
        return false;
      }

      if (this.areSameAccount(this.computedParticipant, account)) {
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
      var _this$getUser;

      return ((_this$getUser = this.getUser) === null || _this$getUser === void 0 ? void 0 : _this$getUser.id) === account.userId;
    }
  }
});

/***/ }),

/***/ "./resources/js/components/AssessmentDetails.vue":
/*!*******************************************************!*\
  !*** ./resources/js/components/AssessmentDetails.vue ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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

/***/ "./resources/js/components/AssessmentDetails.vue?vue&type=script&lang=js&":
/*!********************************************************************************!*\
  !*** ./resources/js/components/AssessmentDetails.vue?vue&type=script&lang=js& ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentDetails_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentDetails.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentDetails.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentDetails_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/AssessmentDetails.vue?vue&type=template&id=7b16d245&scoped=true&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/components/AssessmentDetails.vue?vue&type=template&id=7b16d245&scoped=true& ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentDetails_vue_vue_type_template_id_7b16d245_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentDetails_vue_vue_type_template_id_7b16d245_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssessmentDetails_vue_vue_type_template_id_7b16d245_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AssessmentDetails.vue?vue&type=template&id=7b16d245&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentDetails.vue?vue&type=template&id=7b16d245&scoped=true&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentDetails.vue?vue&type=template&id=7b16d245&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/AssessmentDetails.vue?vue&type=template&id=7b16d245&scoped=true& ***!
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
        _c("div", { staticClass: "text-xs text-gray-300 my-1" }, [
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



/***/ })

}]);