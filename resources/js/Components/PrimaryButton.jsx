export default function PrimaryButton({ className = '', disabled, children, style, ...props }) {
    return (
        <button
            {...props}
            className={
                `inline-flex items-center rounded-md border border-transparent px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#FF7500] focus:ring-offset-2 ${
                    disabled ? 'opacity-25 cursor-not-allowed' : 'hover:opacity-90 active:opacity-80'
                } ` + className
            }
            style={{
                background: 'linear-gradient(90deg, #FD3C00, #FF7500)',
                ...style,
            }}
            disabled={disabled}
        >
            {children}
        </button>
    );
}
