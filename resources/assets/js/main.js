import log from './modules/log'
require('../sass/main.scss');

log.log(1)
log.log(log.add2(2))

class Form {

    constructor () {
        log.log('within form')
        let numbers = [5,10,15].map(num => num * 2)
        console.log(numbers)
    }
}

new Form()

