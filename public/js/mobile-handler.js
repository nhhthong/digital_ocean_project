function Handler() {
    this.hanlder = [];
}

Handler.prototype.push = function(data) {
    this.hanlder.push(data);
};

Handler.prototype._pop = function(id, left, right) {
    if(left >= right) return null;
    let mid = ((left + right) / 2) >> 0;
    if(this.hanlder[mid].id === id){
        return this.hanlder[mid];
    }
    if(id > this.hanlder[mid].id){
        return this._pop(id, mid, right);
    }
    if(id < this.hanlder[mid].id){
        return this._pop(id, left, mid);
    }
};

Handler.prototype.pop = function(id) {
    return this._pop(id, 0, this.hanlder.length);
};

Handler.prototype.length = function() {
    return this.hanlder.length;
};

Handler.prototype.getAll = function() {
    return this.hanlder;
};