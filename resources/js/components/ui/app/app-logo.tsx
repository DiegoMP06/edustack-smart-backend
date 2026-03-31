import AppLogoIcon from '@/components/ui/app/app-logo-icon';

export default function AppLogo() {
    return (
        <>
            <div className="flex aspect-square size-8 items-center justify-center">
                <AppLogoIcon className="size-8" />
            </div>
            <div className="flex flex-1 text-left">
                <p className="truncate leading-tight font-semibold text-foreground">
                    EduStack{' '}
                    <span className="text-sidebar-primary font-bold">
                        Smart
                    </span>
                </p>
            </div>
        </>
    );
}
