

window.addEventListener("load", () => {
    if (!window.gsap || !window.ScrollTrigger) return;

    gsap.registerPlugin(ScrollTrigger);



    document.querySelectorAll(".page").forEach((wrap) => {
        const rateTxt = wrap.querySelector(".line-animation");

        if (!rateTxt) return;

        // 초기 상태
        gsap.set(rateTxt, { "--fill": 0 });

        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: wrap,
                start: "top 80%",
                toggleActions: "play none none reverse"
                // markers: true
            }
        });

        // 붉은 배경 → 왼쪽에서 오른쪽
        tl.to(rateTxt, {
            "--fill": 1,
            duration: 0.6,
            ease: "power2.out"
        })
    });

    let tl2 = gsap.timeline({
        scrollTrigger: {
            trigger: ".page2",
            start: "top 40%",
            toggleActions: "play none none reset",
            // markers: true,
        }
    });

    tl2.fromTo(".page2 .tit-wrap",
        {
            scale: 3,
            opacity: 0,
            filter: "blur(16px)",
            y: 60
        },
        {
            scale: 1,
            opacity: 1,
            y: 60,
            filter: "blur(0px)",
            duration: 0.2,
            ease: "power2.in"
        }
    ).to(".page2 .tit-wrap", {
        y: 0,
        duration: 0.3,
        ease: "power2.out"
    }, "+=0.4");



    //page3
    document.querySelectorAll(".page4 .line-wrap").forEach((wrap) => {
        const line = wrap.querySelector(".line");
        if (!line) return;

        // ✅ 초기화 (devtools에 style="--fill:0" 찍힘)
        gsap.set(line, {"--fill": 0});

        gsap.to(line, {
            "--fill": 1,
            duration: 0.9,
            ease: "power2.out",
            scrollTrigger: {
                trigger: wrap,
                start: "top 80%",
                toggleActions: "play none none reverse",
                // markers: true,
            },
        });
    });

    document.querySelectorAll(".reveal-from-center").forEach((el) => {
        // ✅ 초기: 완전 안보임 + 가운데 폭 0(좌/우 50%씩 잘라서)
        gsap.set(el, {
            opacity: 0,
            y: 10,
            clipPath: "inset(0 50% 0 50%)"
        });

        // ✅ ScrollTrigger 1개만 사용 (반짝 제거)
        gsap.timeline({
            scrollTrigger: {
                trigger: el,
                start: "top 80%",
                toggleActions: "play none none reverse",
                // markers: true
            }
        })
            .to(el, {
                opacity: 1,
                y: 0,
                duration: 0.6,
                ease: "power2.out"
            }, 0)
            .to(el, {
                clipPath: "inset(0 0% 0 0%)", // ✅ 가운데 → 양옆으로 reveal
                duration: 0.4,
                ease: "power2.out"
            }, 0);
    });


    //page7

    const container = document.querySelector(".history-container");
    const items = gsap.utils.toArray(".history-container .history-wrap");
    const arrow = document.querySelector(".history-container .arrow");

    const tl = gsap.timeline({
        scrollTrigger: {
            trigger: container,
            start: "top 80%",
            toggleActions: "play none none reverse",
            // markers: true
        }
    });

    tl.to(items, {
        opacity: 1,
        y: 0,
        duration: 0.9,
        ease: "power2.out",
        stagger: 0.25
    });

    if (arrow) {
        tl.to(arrow, {
            clipPath: "inset(0 0% 0 0%)",  // ✅ 왼쪽→오른쪽 reveal
            duration: 0.5,
            ease: "power2.out"
        }, ">"); // ✅ history-wrap 끝난 다음 바로 실행
    }


    (() => {
        const anis = Array.from(document.querySelectorAll(".page8 .tit2 .ani"));
        if (!anis.length) return;

        anis.forEach((el) => {
            if (el.querySelector(".ani-fill")) return;

            const text = el.textContent;

            const fill = document.createElement("span");
            fill.className = "ani-fill";
            fill.textContent = text;
            fill.setAttribute("aria-hidden", "true");
            el.appendChild(fill);
        });

        const fills = anis
            .map(el => el.querySelector(".ani-fill"))
            .filter(Boolean);

        gsap.set(fills, { clipPath: "inset(0 100% 0 0)" });

        const tlAni = gsap.timeline({
            scrollTrigger: {
                trigger: ".page8",
                start: "top 80%",
                toggleActions: "play none none reverse",
                // markers: true,
            }
        });

        fills.forEach((fill, i) => {
            tlAni.to(fill, {
                clipPath: "inset(0 0% 0 0)",
                duration: 0.4,
                ease: "power2.out"
            }, i === 0 ? 0 : ">+=0.15");
        });
    })();

    document.querySelectorAll(".page8 .bottom-wrap").forEach((wrap) => {
        const rateTxt = wrap.querySelector(".txt-line");
        if (!rateTxt) return;

        gsap.set(rateTxt, { "--fill": 0 });

        gsap.timeline({
            scrollTrigger: {
                trigger: wrap,
                start: "top 80%",
                toggleActions: "play none none reverse",
                // markers: true
            }
        }).to(rateTxt, {
            "--fill": 1,
            duration: 0.9,
            ease: "power2.out"
        });
    });

    document.querySelectorAll(".black-line-wrap").forEach((wrap) => {
        const rateTxt = wrap.querySelector(".black-line");
        if (!rateTxt) return;

        gsap.set(rateTxt, { "--fill": 0 });

        gsap.timeline({
            scrollTrigger: {
                trigger: wrap,
                start: "top 80%",
                toggleActions: "play none none reverse",
                // markers: true
            }
        }).to(rateTxt, {
            "--fill": 1,
            duration: 0.9,
            ease: "power2.out"
        });
    });


    (() => {
        const anis = Array.from(document.querySelectorAll(".page13 .tit2 .ani"));
        if (!anis.length) return;

        anis.forEach((el) => {
            if (el.querySelector(".ani-fill")) return;

            const text = el.textContent;

            const fill = document.createElement("span");
            fill.className = "ani-fill";
            fill.textContent = text;
            fill.setAttribute("aria-hidden", "true");
            el.appendChild(fill);
        });

        const fills = anis
            .map(el => el.querySelector(".ani-fill"))
            .filter(Boolean);

        gsap.set(fills, { clipPath: "inset(0 100% 0 0)" });

        const tlAni = gsap.timeline({
            scrollTrigger: {
                trigger: ".page8",
                start: "top 80%",
                toggleActions: "play none none reverse",
                // markers: true,
            }
        });

        fills.forEach((fill, i) => {
            tlAni.to(fill, {
                clipPath: "inset(0 0% 0 0)",
                duration: 0.4,
                ease: "power2.out"
            }, i === 0 ? 0 : ">+=0.15");
        });
    })();
});

function playClipRelease(scope) {
    if (!scope) return;

    scope.querySelectorAll(".line-animation").forEach(el => {
        gsap.set(el, { "--fill": 0 });
        gsap.to(el, {
            "--fill": 1,
            duration: 0.6,
            ease: "power2.out"
        });
    });
}

const wrap = document.querySelector(".swap-wrap");
const c1 = wrap.querySelector(".content1");
const c2 = wrap.querySelector(".content2");

gsap.set([c1, c2], { autoAlpha: 0 });
gsap.set(c1, { autoAlpha: 1 });

// 최초 1회 실행
playClipRelease(c1);

const SHOW = 2.4;
const FADE = 1.0;

gsap.timeline({ repeat: -1 })
    .to({}, { duration: SHOW })

    // c1 → c2
    .to(c1, { autoAlpha: 0, duration: FADE })
    .to(c2, { autoAlpha: 1, duration: FADE }, "<")
    .add(() => playClipRelease(c2)) // ✅ 여기 중요

    .to({}, { duration: SHOW })

    // c2 → c1
    .to(c2, { autoAlpha: 0, duration: FADE })
    .to(c1, { autoAlpha: 1, duration: FADE }, "<")
    .add(() => playClipRelease(c1)); // ✅ 여기 중요
