export default class Gate {
    constructor(user) {
       this.user = JSON.parse(user);
    }
    isAdmin(){
        return this.user.type == '1';
    }
}
