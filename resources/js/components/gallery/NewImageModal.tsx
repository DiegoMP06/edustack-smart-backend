import type { Dispatch, SetStateAction } from 'react';
import { useEffect } from 'react';
import { Controller, useForm } from 'react-hook-form';
import DropzoneInput from '@/components/dropzone/DropzoneInput';
import InputError from '@/components/ui/app/input-error';
import { Button } from '@/components/ui/shadcn/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/shadcn/dialog';
import { Label } from '@/components/ui/shadcn/label';
import type { ImageFormData } from '@/types';

type NewImageModalProps = {
    isModalActive: boolean;
    setIsModalActive: Dispatch<SetStateAction<boolean>>;
    multipleFiles?: boolean;
    onAddImage: (data: ImageFormData) => void;
    processing: boolean;
};



export default function NewImageModal({
    isModalActive,
    setIsModalActive,
    multipleFiles,
    onAddImage,
    processing,
}: NewImageModalProps) {

    const initialValues: ImageFormData = {
        images: [],
    };

    const {
        control,
        handleSubmit,
        formState: { errors },
        reset,
    } = useForm({
        defaultValues: initialValues,
    });

    useEffect(() => {
        if (!isModalActive) {
            reset();
        }
    }, [isModalActive]);

    return (
        <Dialog open={isModalActive} onOpenChange={setIsModalActive}>
            <DialogContent className="sm:max-w-xl">
                <DialogHeader>
                    <DialogTitle>Nueva imagen</DialogTitle>
                    <DialogDescription>Agrega una nueva imagen a la publicación.</DialogDescription>
                </DialogHeader>

                <form onSubmit={handleSubmit(onAddImage)} className="grid grid-cols-1 gap-6">
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
