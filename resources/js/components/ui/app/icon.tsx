import { CircleHelp, icons } from 'lucide-react';
import type { ComponentProps } from 'react';

type IconProps = ComponentProps<typeof CircleHelp> & {
    iconName: string;
};

const toPascalCase = (value: string): string => {
    return value
        .trim()
        .split(/[-_\s]+/)
        .filter(Boolean)
        .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
        .join('');
};

export function Icon({ iconName, ...props }: IconProps) {
    const normalized = toPascalCase(iconName);
    const IconComponent = icons[normalized as keyof typeof icons] ?? CircleHelp;

    return <IconComponent {...props} />;
}
