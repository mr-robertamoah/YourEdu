module.exports = {
    testRegex: 'D:/web projects/YourEdu/resources/js/tests/.*.spec.js$',
    moduleFileExtensions: [
        'js',
        'json',
        'vue'
    ],
    'transform': {
        '^.+\\.js$': 'D:/web projects/YourEdu/node_modules/babel-jest',
        '.*\\.(vue)$': 'D:/web projects/YourEdu/node_modules/vue-jest'
    },
}