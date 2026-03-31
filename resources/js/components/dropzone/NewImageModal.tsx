import type { Dispatch, SubmitEventHandler, SetStateAction } from 'react';
import { useEffect } from 'react';
import type { Control, FieldErrors, UseFormReset } from 'react-hook-form';
import { Controller } from 'react-hook-form';
import DropzoneInput from '@/components/dropzone/DropzoneInput';
import InputError from '@/components/ui/app/input-error';
import { Button } from '@/components/ui/shadcn/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/shadcn/dialog';
import { Label } from '@/components/ui/shadcn/label';

export type ImageFormData = {
    images: File[];
};

type NewImageModalProps = {
    isModalActive: boolean;
    setIsModalActive: Dispatch<SetStateAction<boolean>>;
    processing?: boolean;
    onSubmit: SubmitEventHandler<HTMLFormElement>;
    control: Control<ImageFormData>;
    errors: FieldErrors<ImageFormData>;
    reset: UseFormReset<ImageFormData>;
    multipleFiles?: boolean;
    title?: string;
    description?: string;
};

export default function NewImageModal({
    isModalActive,
    setIsModalActive,
    processing,
    onSubmit,
    control,
    reset,
    errors,
    multipleFiles,
    title = 'Nueva imagen',
    description = 'Agrega una nueva imagen a la publicación.',
}: NewImageModalProps) {
    useEffect(() => {
        if (!isModalActive) {
            reset();
        }
    }, [isModalActive, reset]);

    return (
        <Dialog open={isModalActive} onOpenChange={setIsModalActive}>
            <DialogContent className="sm:max-w-xl">
                <DialogHeader>
                    <DialogTitle>{title}</DialogTitle>
                    <DialogDescription>{description}</DialogDescription>
                </DialogHeader>

                <form onSubmit={onSubmit} className="grid grid-cols-1 gap-6">
                    <div className="grid gap-2">
                        <Label htmlFor="images">
                            {multipleFiles ? 'Imágenes' : 'Imagen'}:{' '}
                        </Label>

                        <Controller
                            name="images"
                            control={control}
                            rules={{
                                validate: (value) =>
                                    value.length > 0 ||
                                    'La imagen es requerida.',
                            }}
                            render={({ field: { value, onChange } }) => (
                                <DropzoneInput
                                    value={value}
                                    onChange={onChange}
                                    multipleFiles={multipleFiles}
                                />
                            )}
                        />

                        <InputError message={errors.images?.message} />
                    </div>

                    <DialogFooter>
                        <DialogClose asChild>
                            <Button disabled={processing} variant="outline">
                                Cancelar
                            </Button>
                        </DialogClose>

                        <Button type="submit" disabled={processing}>
                            Confirmar
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
