module.exports = {
    testRegex: 'D:/web projects/YourEdu/resources/js/tests/.*.spec.js$',
    moduleFileExtensions: [
        'js',
        'json',
        'vue'
    ],
    'transform': {
        ".*\\.(js)$": "babel-jest",
        ".*\\.(vue)$": "vue-jest",
    },
    "moduleNameMapper": {
        "^@/(.*)$": "D:/web projects/YourEdu/resources/$1"
    }
}