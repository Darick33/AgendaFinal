import { ComponentFixture, TestBed } from '@angular/core/testing';
import { RclavePage } from './rclave.page';

describe('RclavePage', () => {
  let component: RclavePage;
  let fixture: ComponentFixture<RclavePage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(RclavePage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
