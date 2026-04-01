import { Button } from "@/components/ui/shadcn/button";
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from "@/components/ui/shadcn/dialog";
import { useState } from "react";


type ActivitySpeakerModalProps = {
    isModalOpen: boolean;
    setIsModalOpen: (open: boolean) => void;
}

export default function ActivitySpeakerModal({isModalOpen, setIsModalOpen}: ActivitySpeakerModalProps) {
    const [processing, setProcessing] = useState(false);

    return (
        <Dialog open={isModalOpen} onOpenChange={setIsModalOpen}>
            <DialogContent className="sm:max-w-xl">
                <DialogHeader>
                    <DialogTitle>Agregar Colaborador</DialogTitle>
                    <DialogDescription>
                        Aquí puedes agregar un nuevo colaborador a tu proyecto
                    </DialogDescription>
                </DialogHeader>

                <form
                    className="grid grid-cols-1 gap-6"
                >
                    
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
    )
}
