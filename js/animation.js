document.addEventListener("DOMContentLoaded", () => {
    const run = (selector, animName) => {
        const targets = document.querySelectorAll(selector);
        if (!targets.length) return;

        const animClass = `animate__${animName}`;

        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;

                const el = entry.target;
                const delaySec = parseFloat(el.dataset.delay || "0");

                // 딜레이 동안 깜빡임/중복 방지
                if (el.dataset.playing === "1") return;
                el.dataset.playing = "1";

                setTimeout(() => {
                    el.classList.add("animate__animated", animClass);
                    el.style.opacity = "1";

                    el.addEventListener("animationend", () => {
                        el.classList.remove("animate__animated", animClass);
                        el.dataset.playing = "0";
                    }, { once: true });
                }, delaySec * 1000);
            });
        }, { threshold: 0.2 });

        targets.forEach((el) => io.observe(el));
    };

    run(".js-animate-bounce", "bounceInUp");
    run(".js-animate-pulse", "pulse");
    run(".js-animate-lightspeed-left", "lightSpeedInLeft"); // ✅ 추가
    run(".js-animate-lightspeed-right", "lightSpeedInRight"); // ✅ 추가
    run(".js-animate-jackIntheBox", "jackIntheBox"); // ✅ 추가
    run(".js-animate-flash", "flash"); // ✅ 추가
    run(".js-animate-fadeinleft", "fadeinleft"); // ✅ 추가
    run(".js-animate-fadeinright", "fadeinright"); // ✅ 추가
    run(".js-animate-fadeInUp", "fadeInUp"); // ✅ 추가
    run(".js-animate-swing", "swing"); // ✅ 추가
    run(".js-animate-wobble", "wobble"); // ✅ 추가
    run(".js-animate-heatBeat", "heatBeat"); // ✅ 추가
    run(".js-animate-fadeInUp", "fadeInUp"); // ✅ 추가
    run(".js-animate-rotateIn", "rotateIn"); // ✅ 추가
    run(".js-animate-tada", "tada"); // ✅ 추가
    run(".js-animate-flipInX", "flipInX"); // ✅ 추가
});
