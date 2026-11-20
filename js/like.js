(function () {
    var LIKES_API = '';
    var KEY = 'portfolio-likes';
    var counts = {};
    var mine = {};

    try {
        mine = JSON.parse(localStorage.getItem(KEY)) || {};
    } catch (e) {
        mine = {};
    }

    function save() {
        try {
            localStorage.setItem(KEY, JSON.stringify(mine));
        } catch (e) { /* abaikan jika storage diblokir */ }
    }

    // Kartu digandakan oleh carousel, jadi semua salinan diperbarui sekaligus
    function render() {
        document.querySelectorAll('.like').forEach(function (box) {
            var id = box.dataset.project;
            var liked = !!mine[id];
            var total = LIKES_API ? (counts[id] || 0) : (liked ? 1 : 0);
            var btn = box.querySelector('.like__btn');
            btn.setAttribute('aria-pressed', liked);
            btn.querySelector('i').className = (liked ? 'fas' : 'far') + ' fa-heart';
            box.querySelector('.like__count').textContent = total;
        });
    }

    function request(method, body) {
        return fetch(LIKES_API, {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: body ? JSON.stringify(body) : undefined
        }).then(function (res) {
            if (!res.ok) throw new Error('request gagal');
            return res.json();
        });
    }

    function sync(data) {
        counts = data.counts || counts;
        if (data.mine) {
            mine = {};
            data.mine.forEach(function (id) { mine[id] = true; });
        }
        save();
        render();
    }

    // Delegasi klik: berlaku juga untuk kartu hasil gandaan
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.like__btn');
        if (!btn) return;

        var id = btn.closest('.like').dataset.project;
        var liked = !mine[id];

        if (liked) {
            mine[id] = true;
        } else {
            delete mine[id];
        }
        if (LIKES_API) {
            counts[id] = Math.max(0, (counts[id] || 0) + (liked ? 1 : -1));
        }
        save();
        render();

        if (LIKES_API) {
            request('POST', { project: id, like: liked }).then(sync).catch(function () {});
        }
    });

    render();
    if (LIKES_API) {
        request('GET').then(sync).catch(function () {});
    }
})();
