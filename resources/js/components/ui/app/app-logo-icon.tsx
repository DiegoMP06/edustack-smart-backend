import type { SVGAttributes } from 'react';

export default function AppLogoIcon(props: SVGAttributes<SVGElement>) {

    return (
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 64 64"
            {...props}
        >
            <defs>
                <filter id="glow" x="-60%" y="-60%" width="220%" height="220%">
                    <feGaussianBlur stdDeviation="1.5" result="blur" />
                    <feMerge>
                        <feMergeNode in="blur" />
                        <feMergeNode in="SourceGraphic" />
                    </feMerge>
                </filter>

                <linearGradient id="indigoGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" style={{ stopColor: 'var(--logo-grad-start)' }} />
                    <stop offset="100%" style={{ stopColor: 'var(--logo-grad-end)' }} />
                </linearGradient>
            </defs>

            <rect x="6" y="44" width="48" height="10" rx="5" ry="5"
                className="fill-zinc-900 dark:fill-zinc-100"
                transform="rotate(-8, 30, 49)" />

            <rect x="10" y="28" width="38" height="9" rx="4.5" ry="4.5"
                className="fill-zinc-500 dark:fill-zinc-400"
                transform="rotate(-8, 29, 32)" />

            <rect x="14" y="13" width="27" height="9" rx="4.5" ry="4.5"
                fill="url(#indigoGrad)"
                filter="url(#glow)"
                transform="rotate(-8, 27, 17)" />

            <circle cx="37" cy="14" r="5.5"
                className="fill-white dark:fill-indigo-950"
                filter="url(#glow)" />
            <circle cx="37" cy="14" r="3"
                className="fill-indigo-600 dark:fill-indigo-400" />
        </svg>
    );
}
