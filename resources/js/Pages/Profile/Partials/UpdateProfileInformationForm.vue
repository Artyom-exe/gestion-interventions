<script setup>
import { ref } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/ActionMessage.vue";
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: "PUT",
    username: props.user.username,
    email: props.user.email,
    phone_number: props.user.phone_number,
    photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route("user-profile-information.update"), {
        errorBag: "updateProfileInformation",
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route("current-user-photo.destroy"), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title> Informations du profil </template>

        <template #description>
            Mettez à jour les informations de votre compte et votre adresse e-mail.
        </template>

        <template #form>
            <!-- Photo de profil -->
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6 sm:col-span-4">
                <input
                    id="photo"
                    ref="photoInput"
                    type="file"
                    class="hidden"
                    @change="updatePhotoPreview"
                />
                <InputLabel for="photo" value="Photo" />
                <div v-show="!photoPreview" class="mt-2">
                    <img :src="user.profile_photo_url" :alt="user.username" class="rounded-full size-20 object-cover" />
                </div>
                <div v-show="photoPreview" class="mt-2">
                    <span
                        class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(' + photoPreview + ');'"
                    />
                </div>
                <SecondaryButton class="mt-2 me-2" type="button" @click.prevent="selectNewPhoto">
                    Choisir une nouvelle photo
                </SecondaryButton>
                <SecondaryButton v-if="user.profile_photo_path" type="button" class="mt-2" @click.prevent="deletePhoto">
                    Supprimer la photo
                </SecondaryButton>
                <InputError :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- Nom d'utilisateur -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="username" value="Nom d'utilisateur" />
                <TextInput
                    id="username"
                    v-model="form.username"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="username"
                />
                <InputError :message="form.errors.username" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="form.errors.email" class="mt-2" />
                <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                    <p class="text-sm mt-2">
                        Votre adresse e-mail n'est pas vérifiée.
                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Cliquez ici pour renvoyer l'e-mail de vérification.
                        </Link>
                    </p>
                    <div v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                        Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
                    </div>
                </div>
            </div>

            <!-- Numéro de téléphone -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="phone_number" value="Numéro de téléphone" />
                <TextInput
                    id="phone_number"
                    v-model="form.phone_number"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="phone"
                />
                <InputError :message="form.errors.phone_number" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Enregistré.
            </ActionMessage>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Enregistrer
            </PrimaryButton>
        </template>
    </FormSection>
</template>
