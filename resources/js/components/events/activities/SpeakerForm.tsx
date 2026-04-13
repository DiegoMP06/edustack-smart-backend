import type { Control, UseFormRegister } from "react-hook-form";
import { Controller, useFormState, } from "react-hook-form";
import InputError from "@/components/ui/app/input-error";
import { Input } from "@/components/ui/shadcn/input";
import { Label } from "@/components/ui/shadcn/label";
import { Textarea } from "@/components/ui/shadcn/textarea";
import type { SpeakerFormData } from "@/types";
import SocialInput from "./SocialInput";

type SpeakerFormProps = {
    register: UseFormRegister<SpeakerFormData>
    control: Control<SpeakerFormData>
};

export default function SpeakerForm({ register, control }: SpeakerFormProps) {
    const { errors } = useFormState({ control })

    return (
        <>
            <div className="grid gap-2">
                <Label htmlFor="name">Nombre:</Label>
                <Input
                    {...register('name', {
                        required: 'El nombre es requerido',
                        minLength: {
                            value: 3,
                            message:
                                'El nombre debe tener al menos 3 caracteres',
                        },
                    })}
                    id="name"
                    placeholder="Nombre del Ponente"
                />

                <InputError message={errors.name?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="father_last_name">Apellido Paterno:</Label>
                <Input
                    {...register('father_last_name', {
                        required: 'El apellido es requerido',
                        minLength: {
                            value: 3,
                            message:
                                'El apellido debe tener al menos 3 caracteres',
                        },
                    })}
                    id="father_last_name"
                    placeholder="Apellido Paterno del Ponente"
                />

                <InputError message={errors.father_last_name?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="mother_last_name">Apellido Materno:</Label>
                <Input
                    {...register('mother_last_name', {
                        required: 'El apellido es requerido',
                        minLength: {
                            value: 3,
                            message:
                                'El apellido debe tener al menos 3 caracteres',
                        },
                    })}
                    id="mother_last_name"
                    placeholder="Apellido Materno del Ponente"
                />

                <InputError message={errors.mother_last_name?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="biography">Biografía:</Label>

                <Textarea
                    {...register('biography', {
                        required: 'La biografía es requerida',
                        minLength: {
                            value: 50,
                            message:
                                'La biografía debe tener al menos 50 caracteres',
                        },
                    })}
                    id="biography"
                    placeholder="Biografía del Ponente"
                    className="h-60"
                />

                <InputError message={errors.biography?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="email">Correo:</Label>

                <Input
                    {...register('email', {
                        required: 'El correo es requerido',
                        pattern: {
                            value: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i,
                            message: 'El correo no es valido',
                        }
                    })}
                    id="email"
                    type="email"
                    placeholder="Correo del Ponente"
                />

                <InputError message={errors.email?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="job_title">Cargo:</Label>
                <Input
                    {...register('job_title')}
                    id="job_title"
                    placeholder="Cargo del Ponente"
                />

                <InputError message={errors.job_title?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="company">Compañía:</Label>

                <Input
                    {...register('company')}
                    id="company"
                    placeholder="Compañía del Ponente"
                />

                <InputError message={errors.company?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="social">Redes Sociales:</Label>

                <Controller
                    control={control}
                    name="social"
                    rules={{
                        validate: (value) =>
                            value!.length > 0 ||
                            'Debe seleccionar al menos una red social',
                    }}
                    render={({ field: { onChange, value } }) => (
                        <SocialInput onChange={onChange} value={value} />
                    )}
                />

                <InputError message={errors.social?.message} />
            </div>
        </>

    )
}


