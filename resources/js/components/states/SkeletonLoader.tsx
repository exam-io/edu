interface SkeletonLoaderProps {
    className?: string;
}

export function SkeletonLoader({ className }: SkeletonLoaderProps) {
    return <div aria-hidden="true" className={`skeleton ${className ?? ''}`.trim()} />;
}
