(function () {

    if (window.WavoImages) return;

    var channel = null;
    try {
        if ('BroadcastChannel' in window) {
            channel = new BroadcastChannel('wavo-images');
        }
    } catch (e) { channel = null; }

    function buildImg(url, alt) {
        var img = document.createElement('img');
        img.src = url;
        img.alt = alt || '';
        return img;
    }

    function setImageIn(el, url) {
        var existing = el.querySelector(':scope > img');
        if (existing) {
            existing.src = url;
        } else {
            el.replaceChildren(buildImg(url, el.dataset.imageAlt));
        }
    }

    function clearTurboCache() {
        if (window.Turbo && window.Turbo.cache && window.Turbo.cache.clear) {
            window.Turbo.cache.clear();
        }
    }

    function applyUserAvatar(userId, url) {
        document
            .querySelectorAll('[data-user-avatar="' + userId + '"]')
            .forEach(function (el) { setImageIn(el, url); });
    }

    function applyPlaylistCover(playlistId, url) {
        document
            .querySelectorAll('[data-playlist-cover="' + playlistId + '"]')
            .forEach(function (el) { setImageIn(el, url); });

        document
            .querySelectorAll('[data-playlist-cover-bg="' + playlistId + '"]')
            .forEach(function (el) {
                el.style.background = '';
                el.style.backgroundImage = 'url("' + String(url).replace(/"/g, '%22') + '")';
            });
    }

    function apply(msg) {
        if (!msg) return;
        if (msg.type === 'user-avatar') applyUserAvatar(msg.id, msg.url);
        if (msg.type === 'playlist-cover') applyPlaylistCover(msg.id, msg.url);
        clearTurboCache();
    }

    function announce(msg) {
        apply(msg);
        if (channel) channel.postMessage(msg);
    }

    if (channel) {
        channel.onmessage = function (e) { apply(e.data); };
    }

    function upload(url, fieldName, file) {
        var token = document.querySelector('meta[name="csrf-token"]');
        var body = new FormData();
        body.append(fieldName, file);

        return fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token ? token.content : ''
            },
            body: body,
            credentials: 'same-origin'
        }).then(function (res) {
            return res.json().catch(function () { return {}; }).then(function (data) {
                if (!res.ok) {
                    var msg = (data.errors && Object.values(data.errors)[0] && Object.values(data.errors)[0][0])
                        || data.message
                        || 'Upload failed.';
                    throw new Error(msg);
                }
                return data;
            });
        });
    }

    window.WavoImages = {
        uploadUserAvatar: function (url, file) {
            return upload(url, 'profile_photo', file).then(function (data) {
                announce({ type: 'user-avatar', id: data.user_id, url: data.photo_url });
                return data;
            });
        },
        uploadPlaylistCover: function (url, playlistId, file) {
            return upload(url, 'cover', file).then(function (data) {
                announce({ type: 'playlist-cover', id: playlistId, url: data.cover_url });
                return data;
            });
        }
    };

})();