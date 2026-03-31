// Components
import { Form, Head } from '@inertiajs/react';
import TextLink from '@/components/ui/app/text-link';
import { Button } from '@/components/ui/shadcn/button';
import { Spinner } from '@/components/ui/shadcn/spinner';
import AuthLayout from '@/layouts/auth-layout';
import { logout } from '@/routes';
import { send } from '@/routes/verification';

export default function VerifyEmail({ status }: { status?: string }) {
    return (
        <AuthLayout
            title="Verifique su correo electrónico"
            description="Verifique su correo electrónico haciendo clic en el enlace que le acabamos de enviar."
        >
            <Head title="Verificación de correo electrónico" />

            {status === 'verification-link-sent' && (
                <div className="mb-4 text-center text-sm font-medium text-green-600">
                    Se ha enviado un nuevo enlace de verificación al correo
                    electrónico que proporcionaste durante el registro.
                </div>
            )}

            <Form {...send.form()} className="space-y-6 text-center">
                {({ processing }) => (
                    <>
                        <Button disabled={processing} variant="secondary">
                            {processing && <Spinner />}
                            Reenviar correo de verificación
                        </Button>

                        <TextLink
                            href={logout()}
                            className="mx-auto block text-sm"
                        >
                            Cerrar sesión
                        </TextLink>
                    </>
                )}
            </Form>
        </AuthLayout>
    );
}
