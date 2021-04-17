const _MS_PER_DAY = 1000 * 60 * 60 * 24;
const _MS_PER_HOUR = 1000 * 60 * 60;
const _MS_PER_SECOND = 1000 ;

const dates = {
    dayShort(int){
        if (int === 0) {
            return 'Sun'
        } else if (int === 1) {
            return 'Mon'
        } else if (int === 2) {
            return 'Tue'
        } else if (int === 3) {
            return 'Wed'
        } else if (int === 4) {
            return 'Thu'
        } else if (int === 5) {
            return 'Fri'
        } else if (int === 6) {
            return 'Sat'
        }
    },
    monthShort(int){
        if (int === 0) {
            return 'Jan'
        } else if (int === 1) {
            return 'Feb'
        } else if (int === 2) {
            return 'Mar'
        } else if (int === 3) {
            return 'Apr'
        } else if (int === 4) {
            return 'May'
        } else if (int === 5) {
            return 'Jun'
        } else if (int === 6) {
            return 'Jul'
        } else if (int === 7) {
            return 'Aug'
        } else if (int === 8) {
            return 'Sep'
        } else if (int === 9) {
            return 'Oct'
        } else if (int === 10) {
            return 'Nov'
        } else if (int === 11) {
            return 'Dec'
        }
    },
    toDate(date){
        return Date.UTC(date.getFullYear(), date.getMonth(), date.getDate())
    },
    age(date){
        let main = new Date(date)
        let now = new Date()

        let age = now.getFullYear() - main.getFullYear()
        let birthDate = new Date(now.getFullYear(),main.getMonth(),main.getDate())

        age = now > birthDate ? age-- : age
        return age
    },
    dateDiff(date1, date2){
        return Math.abs(Math.floor((date1 - date2)/_MS_PER_DAY))
    },
    dateReadable(date, format = null){
        return format ? new Date(date).toDateString(format) : new Date(date).toDateString()
    },
    createdAt(date){
        let theDate = new Date(date)
        let now = new Date
        let div = (now - theDate)
        let divInSeconds = div/_MS_PER_SECOND
        let divInHours = div/_MS_PER_HOUR
        let divInDays = div/_MS_PER_DAY

        if (divInSeconds < 60) {
            return 'now'
        } else if (divInSeconds > 60 && divInSeconds < 300) {
            return 'few minutes ago'
        } else if (divInSeconds > 300 && divInHours < 1) {
            return `${Math.floor(divInSeconds/60)} minutes ago`
        } else if (divInHours > 1 && divInHours < 2) {
            return 'an hour ago'
        } else if (divInHours > 2 && divInHours < 24) {
            return `${Math.floor(divInHours)} hours ago`
        } else if (divInDays > 1 && divInDays < 2) {
            return `${Math.floor(divInHours)} hours ago`
        } else if (divInDays > 2 && divInDays < 3) {
            return `2 days ago`
        } else if (divInDays > 3) {
            return `${this.dayShort(theDate.getDay())}, ${theDate.getDate()} ${this.monthShort(theDate.getMonth())}, ${theDate.getFullYear()}`
        }
    },
    dueAt(date){
        let theDate = new Date(date)
        let now = new Date
        let div = (theDate - now)
        let divInSeconds = div/_MS_PER_SECOND
        let divInHours = div/_MS_PER_HOUR
        let divInDays = div/_MS_PER_DAY

        if (divInSeconds < 60) {
            return 'now'
        } else if (divInSeconds > 60 && divInSeconds < 120) {
            return 'few minutes time'
        } else if (divInSeconds > 120 && divInHours < 1) {
            return `${Math.floor(divInSeconds/60)} minutes time`
        } else if (divInHours > 1 && divInHours < 2) {
            return 'an hour time'
        } else if (divInHours > 2 && divInHours < 24) {
            return `${Math.floor(divInHours)} hours time`
        } else if (divInDays > 1 && divInDays < 2) {
            return `${Math.floor(divInHours)} hours time`
        } else if (divInDays > 2 && divInDays < 3) {
            return `2 days time`
        } else if (divInDays > 3) {
            return `${this.dayShort(theDate.getDay())}, ${theDate.getDate()} ${this.monthShort(theDate.getMonth())}, ${theDate.getFullYear()}`
        }
    },
    tomorrow() {
        let now = new Date()

        return new Date(Date.UTC(now.getFullYear(),now.getMonth(), now.getDate() + 1))
    }
}

const files = {
    fileType(file){
        if (file.type.includes('image')) {
            return 'image'
        } else if (file.type.includes('video')) {
            return 'video'
        } else if (file.type.includes('audio')) {
            return 'audio'
        } else if (file.type.includes('application')) {
            return 'application'
        }
    }
}

const strings = {
    trim(string, int = 100,){
        if (string) {
            if (string.length > int) {
                return `${string.slice(0,int)}...`
            } else {
                return string
            }
        }
    },
    capitalize(string) {
        `${string.slice(0,1).toUpperCase()}${string.slice(1)}`
    },
    getNumberString(int) {
        switch (int) {
            case 1:
                return 'one'
                break;  
            case 2:
                return 'two'
                break;  
            case 3:
                return 'three'
                break;  
            case 4:
                return 'four'
                break;  
            case 5:
                return 'five'
                break;  
            case 6:
                return 'six'
                break;  
            case 7:
                return 'seven'
                break;  
            case 8:
                return 'eight'
                break;  
            case 9:
                return 'nine'
                break;  
            case 10:
                return 'ten'
                break;        
            default:
                break;
        }
    },
    getNumberLetter(int) {
        switch (int) {
            case 1:
                return 'a'
            case 2:
                return 'b'
            case 3:
                return 'c'
            case 4:
                return 'd'
            case 5:
                return 'e'
            case 6:
                return 'f'
            case 7:
                return 'g'
            case 8:
                return 'h'
            case 9:
                return 'i'
            case 10:
                return 'j'  
            case 11:
                return 'k'
            case 12:
                return 'l'
            case 13:
                return 'm'
            case 14:
                return 'n'
            case 15:
                return 'o'
            case 16:
                return 'p'
            case 17:
                return 'q'
            case 18:
                return 'r'
            case 19:
                return 'q'
            case 20:
                return 't'
            case 20:
                return 'u'
            case 21:
                return 'v'
            case 22:
                return 'w'
            case 23:
                return 'x'
            case 24:
                return 'y'
            case 25:
                return 'z'
            default:
                if (int > 25 && int <= 50) {
                    return `a${this.getNumberLetter(int - 25)}`                    
                }
                if (int > 50 && int <= 75) {
                    return `b${this.getNumberLetter(int - 50)}`                    
                }
                break;
        }
    },
    arrayToNewLineStrings(array){ //for turning arrays to strings with newlines
        let newString = ''
        for (let i = 0; i < array.length; i++) {
            if (i === array.length - 1) {
                newString += array[i]
            } else {
                newString += array[i] + "\n\n"
            }
        }

        return newString
    },
    getAccount(account){
        if (account.toString().toLocaleLowerCase().includes('learner')) {
            return 'learner'
        } else if (account.toString().toLocaleLowerCase().includes('parent')) {
            return 'parent'
        } else if (account.toString().toLocaleLowerCase().includes('facilitator')) {
            return 'facilitator'
        } else if (account.toString().toLocaleLowerCase().includes('professional')) {
            return 'professional'
        } else if (account.toString().toLocaleLowerCase().includes('school')) {
            return 'school'
        } else if (account.toString().toLocaleLowerCase().includes('post')) {
            return 'post'
        } else if (account.toString().toLocaleLowerCase().includes('discussion')) {
            return 'discussion'
        } else if (account.toString().toLocaleLowerCase().includes('comment')) {
            return 'comment'
        } else if (account.toString().toLocaleLowerCase().includes('subject')) {
            return 'subject'
        } else if (account.toString().toLocaleLowerCase().includes('grade')) {
            return 'grade'
        }
    }
}

export {dates, files, strings}