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
import { DocWorkspaceDashboardComponent } from './features/doc-workspace-dashboard/doc-workspace-dashboard.component';
import { DocWorkspaceFormComponent } from './features/doc-workspace-form/doc-workspace-form.component';
import { DocWorkspaceListComponent } from './features/doc-workspace-list/doc-workspace-list.component';
import { DocWorkspaceTableComponent } from './features/doc-workspace-table/doc-workspace-table.component';
import { NuaUserTableComponent } from './features/nua-user-table/nua-user-table.component';
import { DocDocumentTableComponent } from './pages/doc-document-table/doc-document-table.component';
import { LandingPageComponent } from './pages/landing-page/landing-page.component';
import { ProjectDashboardComponent } from './pages/project-dashboard/project-dashboard.component';
import { TestPageComponent } from './pages/test-page/test-page.component';
import { DocumentDashboardComponent } from './features/document-dashboard/document-dashboard.component';
import { AdminHomeComponent } from './features/admin-home/admin-home.component';

const routes: Routes = [
  { path: '', component: NewSigninComponent },
  { path: 'workspace-table', component: DocWorkspaceTableComponent },
  { path: 'workspaces', component: DocWorkspaceListComponent },
  { path: 'documents', component: DocDocumentTableComponent },
  { path: 'workspace-dashboard/:id', component: ProjectDashboardComponent, resolve: { data: DataResolver }  },
  { path: 'document-dashboard/:id', component: DocumentDashboardComponent, resolve: { data: DataResolver }  },
  { path: 'team', component: NuaUserTableComponent },
  { path: 'admin', component: AdminHomeComponent },
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
