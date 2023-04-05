import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { RouteParameterResolver } from 'sql-components';
import { ForcedLogoutComponent } from './auth/forced-logout/forced-logout.component';
import { InvalidTokenComponent } from './auth/invalid-token/invalid-token.component';
import { NewSigninComponent } from './auth/new-signin/new-signin.component';
import { UserEnrollComponent } from './auth/user-enroll/user-enroll.component';
import { UserLogoutComponent } from './auth/user-logout/user-logout.component';
import { DataResolver, MenuResolver, PingResolver, UserResolver } from './data.resolver';
import { AdminDashboardComponent } from './features/admin-dashboard/admin-dashboard.component';
import { LandingPageComponent } from './pages/landing-page/landing-page.component';
import { TestPageComponent } from './pages/test-page/test-page.component';

const routes: Routes = [
  { path: '', component: NewSigninComponent },
  { path: 'test', component: TestPageComponent, resolve: { parameters: RouteParameterResolver } },
  { path: 'test/:id', component: TestPageComponent, resolve: { parameters: RouteParameterResolver } },
  { path: 'test/:id/:id2', component: TestPageComponent, resolve: { parameters: RouteParameterResolver } },
  { path: 'test/:id/:id2/:id3', component: TestPageComponent, resolve: { parameters: RouteParameterResolver } },    
  { path: 'sadmin', component: AdminDashboardComponent, resolve: { data: DataResolver, userdata: UserResolver, menudata: MenuResolver }, },
  { path: 'landing-page', component: LandingPageComponent, resolve: { menudata: MenuResolver, data: DataResolver, userdata: UserResolver }},
  { path: 'enroll/:id', component: UserEnrollComponent , resolve: { data: DataResolver }, },
  { path: 'e/:id', component: UserEnrollComponent, resolve: { data: DataResolver }, },
  { path: 'e', component: UserEnrollComponent, resolve: { data: DataResolver }, },
  { path: 'forced-off/:id', component: ForcedLogoutComponent },
  { path: 'forced-off', component: ForcedLogoutComponent },
  { path: 'sign-in', component: NewSigninComponent },
  { path: 'error', component: InvalidTokenComponent },
  { path: 'enroll', component: UserEnrollComponent, resolve: { data: DataResolver }, },
  { path: 'user-logout', component: UserLogoutComponent },
  { path: 'enroll/:id', component: UserEnrollComponent },
  { path: 'enroll', component: UserEnrollComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
