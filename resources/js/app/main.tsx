import React from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter } from 'react-router-dom';
import { App } from './App';
import { AuthProvider } from '@modules/auth/components/AuthProvider';
import { PermissionProvider } from '@modules/auth/components/PermissionProvider';
import { TenantProvider } from '@modules/auth/components/TenantProvider';
import { ThemeProvider } from '@providers/ThemeProvider';
import { LocaleProvider } from '@providers/LocaleProvider';
import { InstituteProvider } from '@modules/institutes';
import { AcademicProvider } from '@modules/academic';

const rootElement = document.getElementById('app');

if (!rootElement) {
    throw new Error('EduOS root element not found.');
}

createRoot(rootElement).render(
    <React.StrictMode>
        <AuthProvider>
            <TenantProvider>
                <PermissionProvider>
                    <ThemeProvider>
                        <LocaleProvider>
                            <InstituteProvider>
                                <AcademicProvider>
                                    <BrowserRouter>
                                        <App />
                                    </BrowserRouter>
                                </AcademicProvider>
                            </InstituteProvider>
                        </LocaleProvider>
                    </ThemeProvider>
                </PermissionProvider>
            </TenantProvider>
        </AuthProvider>
    </React.StrictMode>,
);
