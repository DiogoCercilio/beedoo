function GroupList() {
    this.init = function () {
        this.$dtable = new BeedooDatatable('#table-groups', '/groups/get', {});
        this.$dtable.init();
        console.log(this.$dtable);
    }
}

postList = new GroupList();

$(document).ready(function () {
    postList.init();
});