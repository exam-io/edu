import i18n from 'i18next';
import LanguageDetector from 'i18next-browser-languagedetector';
import { initReactI18next } from 'react-i18next';
import en from '@locales/en.json';
import hi from '@locales/hi.json';

void i18n
    .use(LanguageDetector)
    .use(initReactI18next)
    .init({
        fallbackLng: 'en',
        supportedLngs: ['en', 'hi'],
        interpolation: {
            escapeValue: false,
        },
        resources: {
            en: { translation: en },
            hi: { translation: hi },
        },
    });

export default i18n;
