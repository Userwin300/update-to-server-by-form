import { Routes } from '@angular/router';
import {MyAppComponent } from './my-app/my-app.component'
import {LinkComponent } from './link/link.component'

export const routes: Routes = [
    {path: 'a1', component: MyAppComponent },
    {path: 'new', component: LinkComponent },
];
