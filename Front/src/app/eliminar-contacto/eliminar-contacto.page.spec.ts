import { ComponentFixture, TestBed } from '@angular/core/testing';
import { EliminarContactoPage } from './eliminar-contacto.page';

describe('EliminarContactoPage', () => {
  let component: EliminarContactoPage;
  let fixture: ComponentFixture<EliminarContactoPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(EliminarContactoPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
