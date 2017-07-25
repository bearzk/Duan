function add2 (number) {
    return number + 2;
}


function log (message) {
    console.log(add2(message));
}

export default {
    add2: add2,
    log: log
}
