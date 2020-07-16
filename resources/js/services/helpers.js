const _MS_PER_DAY = 1000 * 60 * 60 * 24;

const dates = {
    
    toDate(date){
        return Date.UTC(date.getFullYear(), date.getMonth(), date.getDate())
    },
    
    dateDiff(date1, date2){
        return Math.abs(Math.floor((date1 - date2)/_MS_PER_DAY))
    },
    dateReadable(date, format = null){
        return format ? new Date(date).toDateString(format) : new Date(date).toDateString()
    },
}

export {dates}